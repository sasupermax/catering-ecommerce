<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product']);

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por estado de pago
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtro por fecha de entrega (opcional)
        if ($request->filled('delivery_date')) {
            $query->whereDate('delivery_date', $request->delivery_date);
        }

        // Búsqueda por número de orden, nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Paginación con selector de cantidad
        $perPage = $request->input('per_page', 5);
        $orders = $query->latest()->paginate($perPage)->appends($request->except('page'));

        // Estadísticas generales (todos los pedidos)
        $stats = [
            'total' => Order::where('payment_status', 'paid')->count(),
            'pending' => Order::where('status', 'pending')
                              ->where('payment_status', 'paid')
                              ->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'today_deliveries' => Order::whereDate('delivery_date', today())
                                       ->where('payment_status', 'paid')
                                       ->whereIn('status', ['pending', 'confirmed'])
                                       ->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,delivered,cancelled',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $order->update(array_filter($validated));

        return redirect()->back()->with('success', 'Pedido actualizado correctamente.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'status' => $order->status
        ]);
    }

    public function search(Request $request)
    {
        $order = null;
        
        if ($request->filled('order_number')) {
            $order = Order::with(['items.product'])
                          ->where('order_number', $request->order_number)
                          ->first();
        }

        return view('admin.orders.search', compact('order'));
    }

    public function exportDaily(Request $request)
    {
        // Obtener fecha (hoy por defecto)
        $date = $request->input('date', today()->format('Y-m-d'));
        
        // Obtener pedidos del día con pago confirmado
        $orders = Order::with(['items.product'])
            ->whereDate('delivery_date', $date)
            ->where('payment_status', 'paid')
            ->orderBy('delivery_time')
            ->get();

        // Crear nuevo spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Título
        $sheet->setCellValue('A1', 'PEDIDOS DEL DÍA - ' . \Carbon\Carbon::parse($date)->format('d/m/Y'));
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Encabezados
        $headers = [
            'Nro Pedido',
            'Cliente',
            'Teléfono',
            'Dirección',
            'Productos',
            'Total',
            'Estado Pago',
            '✓ Entregado',
            'Hora Entrega',
            'Firma'
        ];
        
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '3', $header);
            $column++;
        }
        
        // Estilo encabezados
        $sheet->getStyle('A3:J3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);
        
        // Datos
        $row = 4;
        foreach ($orders as $order) {
            // Lista de productos
            $productsList = [];
            foreach ($order->items as $item) {
                $unit = $item->product->type === 'N' ? 'un' : 'kg';
                $qty = $item->product->type === 'N' ? (int)$item->quantity : number_format($item->quantity, 1);
                $productsList[] = "• {$item->product->name} ({$qty} {$unit})";
            }
            $productsText = implode("\n", $productsList);
            
            // Estado de pago legible
            $paymentStatus = match($order->payment_status) {
                'paid' => 'Pagado',
                'pending' => 'Pendiente',
                default => ucfirst($order->payment_status)
            };
            
            // Llenar fila
            $sheet->setCellValue('A' . $row, $order->order_number);
            $sheet->setCellValue('B' . $row, $order->customer_name);
            $sheet->setCellValue('C' . $row, $order->customer_phone);
            $sheet->setCellValue('D' . $row, $order->delivery_address);
            $sheet->setCellValue('E' . $row, $productsText);
            $sheet->setCellValue('F' . $row, '$' . number_format($order->total, 2));
            $sheet->setCellValue('G' . $row, $paymentStatus);
            $sheet->setCellValue('H' . $row, ''); // Checkbox manual
            $sheet->setCellValue('I' . $row, ''); // Hora de entrega
            $sheet->setCellValue('J' . $row, ''); // Firma
            
            // Estilo de fila
            $fillColor = $row % 2 == 0 ? 'F2F2F2' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor]
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true
                ]
            ]);
            
            // Altura de fila según cantidad de productos
            $sheet->getRowDimension($row)->setRowHeight(max(30, count($order->items) * 15));
            
            $row++;
        }
        
        // Ajustar anchos de columna
        $sheet->getColumnDimension('A')->setWidth(15); // Nro Pedido
        $sheet->getColumnDimension('B')->setWidth(25); // Cliente
        $sheet->getColumnDimension('C')->setWidth(15); // Teléfono
        $sheet->getColumnDimension('D')->setWidth(35); // Dirección
        $sheet->getColumnDimension('E')->setWidth(40); // Productos
        $sheet->getColumnDimension('F')->setWidth(12); // Total
        $sheet->getColumnDimension('G')->setWidth(15); // Estado Pago
        $sheet->getColumnDimension('H')->setWidth(12); // Entregado
        $sheet->getColumnDimension('I')->setWidth(15); // Hora Entrega
        $sheet->getColumnDimension('J')->setWidth(20); // Firma
        
        // Altura de las filas de título y encabezado
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(20);
        
        // Generar archivo
        $fileName = 'Pedidos_' . \Carbon\Carbon::parse($date)->format('Y-m-d') . '.xlsx';
        
        // Configurar headers para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf(Request $request)
    {
        // Obtener fecha (hoy por defecto)
        $date = $request->input('date', today()->format('Y-m-d'));
        
        // Obtener pedidos del día con pago confirmado
        $orders = Order::with(['items.product'])
            ->whereDate('delivery_date', $date)
            ->where('payment_status', 'paid')
            ->orderBy('delivery_time')
            ->get();

        $dateFormatted = \Carbon\Carbon::parse($date)->format('d/m/Y');
        
        $pdf = Pdf::loadView('admin.orders.pdf', compact('orders', 'dateFormatted'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif'
            ]);
        
        $fileName = 'Pedidos_' . \Carbon\Carbon::parse($date)->format('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }
}
