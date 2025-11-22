<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Recibido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #d81d25;
        }
        .header h1 {
            color: #d81d25;
            margin: 0;
            font-size: 28px;
        }
        .header .badge {
            background-color: #ffd90f;
            color: #333;
            padding: 5px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
        }
        .content {
            padding: 30px 0;
        }
        .order-info {
            background-color: #f9f9f9;
            border-left: 4px solid #d81d25;
            padding: 15px;
            margin: 20px 0;
        }
        .order-info h2 {
            color: #d81d25;
            margin: 0 0 15px 0;
            font-size: 20px;
        }
        .order-info p {
            margin: 8px 0;
            font-size: 15px;
        }
        .order-info strong {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th {
            background-color: #d81d25;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .products-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
            font-size: 18px;
        }
        .total-row td {
            color: #d81d25;
        }
        .alert {
            background-color: #fff3cd;
            border-left: 4px solid #ffd90f;
            padding: 15px;
            margin: 20px 0;
        }
        .alert strong {
            color: #856404;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #d81d25;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Pedido Recibido!</h1>
            <span class="badge">PENDIENTE DE PAGO</span>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $order->customer_name }}</strong>,</p>
            
            <p>Hemos recibido tu pedido correctamente. A continuación encontrarás el resumen de tu compra:</p>

            <div class="order-info">
                <h2>Información del Pedido</h2>
                <p><strong>Número de Pedido:</strong> #{{ $order->order_number }}</p>
                <p><strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</p>
                <p><strong>Dirección de Entrega:</strong> {{ $order->delivery_address }}</p>
                <p><strong>Estado del Pago:</strong> 
                    @if($order->payment_status === 'paid')
                        ✅ Pagado
                    @else
                        ⏳ Pendiente
                    @endif
                </p>
            </div>

            <h3 style="color: #d81d25; margin-top: 30px;">Productos Solicitados</h3>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th style="text-align: center;">Cantidad</th>
                        <th style="text-align: right;">Precio</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right;">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">TOTAL:</td>
                        <td style="text-align: right;">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($order->notes)
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>Notas del pedido:</strong>
                <p style="margin: 5px 0 0 0;">{{ $order->notes }}</p>
            </div>
            @endif

            <div class="alert">
                <strong>⚠️ Importante:</strong> Tu pedido será confirmado una vez que se apruebe el pago. Recibirás un correo de confirmación cuando esto suceda.
            </div>

            <p>Si tienes alguna consulta o necesitas realizar cambios, puedes cancelar o modificar tu pedido hasta 24 horas antes de la fecha de entrega.</p>
        </div>

        <div class="footer">
            <p><strong>SUPERMAX S.A.</strong></p>
            <p>Av. Maipu 359, Capital, Corrientes</p>
            <p>
                📧 <a href="mailto:supermax@supermaxsa.com.ar">supermax@supermaxsa.com.ar</a> | 
                📞 <a href="tel:+543794000000">+54 379 400-0000</a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Este es un correo automático, por favor no responder.
            </p>
        </div>
    </div>
</body>
</html>
