<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Confirmado</title>
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
            border-bottom: 3px solid #28a745;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 28px;
        }
        .header .success-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .header .badge {
            background-color: #28a745;
            color: white;
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
        .success-message {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .success-message p {
            margin: 0;
            color: #155724;
            font-size: 16px;
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
        .highlight-box {
            background-color: #fff3cd;
            border: 2px solid #ffd90f;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .highlight-box h3 {
            color: #856404;
            margin: 0 0 10px 0;
        }
        .highlight-box p {
            margin: 5px 0;
            font-size: 16px;
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
            <div class="success-icon">✅</div>
            <h1>¡Pago Confirmado!</h1>
            <span class="badge">PEDIDO APROBADO</span>
        </div>

        <div class="content">
            <p>Hola <strong>{{ $order->customer_name }}</strong>,</p>
            
            <div class="success-message">
                <p><strong>¡Excelente noticia! Tu pago ha sido procesado exitosamente y tu pedido está confirmado.</strong></p>
            </div>

            <p>Ya comenzamos a preparar todo para tu entrega. A continuación encontrarás el resumen completo:</p>

            <div class="order-info">
                <h2>Información del Pedido</h2>
                <p><strong>Número de Pedido:</strong> #{{ $order->order_number }}</p>
                <p><strong>Fecha de Entrega:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</p>
                <p><strong>Dirección de Entrega:</strong> {{ $order->delivery_address }}</p>
                <p><strong>Estado del Pago:</strong> ✅ Pagado</p>
                <p><strong>Fecha de Pago:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}hs</p>
            </div>

            <h3 style="color: #d81d25; margin-top: 30px;">Productos de tu Pedido</h3>
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
                        <td colspan="3" style="text-align: right;">TOTAL PAGADO:</td>
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

            <div class="highlight-box">
                <h3>📅 ¿Cuándo recibiré mi pedido?</h3>
                <p>Tu pedido será entregado el <strong>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</strong></p>
                <p>durante el transcurso del día en la dirección indicada.</p>
            </div>

            <div style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #d81d25; margin-top: 0;">📋 Política de Cancelación</h3>
                <p>Recordá que podés cancelar o modificar tu pedido sin costo hasta <strong>24 horas antes</strong> de la fecha de entrega.</p>
                <p style="margin-bottom: 0;">Si necesitás realizar algún cambio, contactanos lo antes posible.</p>
            </div>

            <p style="font-size: 16px; color: #28a745; font-weight: bold;">¡Gracias por confiar en SUPERMAX Catering! 🎉</p>
        </div>

        <div class="footer">
            <p><strong>SUPERMAX S.A.</strong></p>
            <p>Av. Maipu 359, Capital, Corrientes</p>
            <p>
                📧 <a href="mailto:supermax@supermaxsa.com.ar">supermax@supermaxsa.com.ar</a> | 
                📞 <a href="tel:+543794000000">+54 379 400-0000</a>
            </p>
            <p style="margin-top: 20px;">
                <a href="{{ route('home') }}" style="background-color: #d81d25; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block;">
                    Ver más productos
                </a>
            </p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Este es un correo automático, por favor no responder.
            </p>
        </div>
    </div>
</body>
</html>
