<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos del Día</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            padding: 15px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4472C4;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            border: 1px solid #333;
        }
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .product-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .product-list li {
            margin-bottom: 2px;
        }
        .checkbox-cell {
            text-align: center;
            font-size: 14px;
        }
        .col-order { width: 8%; }
        .col-client { width: 12%; }
        .col-phone { width: 9%; }
        .col-address { width: 20%; }
        .col-products { width: 23%; }
        .col-total { width: 7%; }
        .col-payment { width: 7%; }
        .col-delivered { width: 5%; }
        .col-time { width: 6%; }
        .col-signature { width: 8%; }
    </style>
</head>
<body>
    <h1>PEDIDOS A ENTREGAR - {{ $dateFormatted }}</h1>
    
    <table>
        <thead>
            <tr>
                <th class="col-order">Nro Pedido</th>
                <th class="col-client">Cliente</th>
                <th class="col-phone">Teléfono</th>
                <th class="col-address">Dirección</th>
                <th class="col-products">Productos</th>
                <th class="col-total">Total</th>
                <th class="col-payment">Estado Pago</th>
                <th class="col-delivered">Entregado</th>
                <th class="col-time">Hora Entrega</th>
                <th class="col-signature">Firma</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="col-order">{{ $order->order_number }}</td>
                <td class="col-client">{{ $order->customer_name }}</td>
                <td class="col-phone">{{ $order->customer_phone }}</td>
                <td class="col-address">{{ $order->delivery_address }}</td>
                <td class="col-products">
                    <ul class="product-list">
                        @foreach($order->items as $item)
                        <li>
                            • {{ $item->product->name }} 
                            ({{ $item->product->type === 'N' ? (int)$item->quantity : number_format($item->quantity, 1) }} 
                            {{ $item->product->type === 'N' ? 'un' : 'kg' }})
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td class="col-total">${{ number_format($order->total, 2) }}</td>
                <td class="col-payment">
                    @switch($order->payment_status)
                        @case('paid')
                            Pagado
                            @break
                        @case('pending')
                            Pendiente
                            @break
                        @default
                            {{ ucfirst($order->payment_status) }}
                    @endswitch
                </td>
                <td class="col-delivered checkbox-cell">[ ]</td>
                <td class="col-time"></td>
                <td class="col-signature"></td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">
                    No hay pedidos para entregar en esta fecha
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
