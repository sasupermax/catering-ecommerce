<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        // Configurar el SDK de Mercado Pago
        $accessToken = config('services.mercadopago.access_token');
        MercadoPagoConfig::setAccessToken($accessToken);
        
        // Configurar entorno (LOCAL para desarrollo)
        if (config('services.mercadopago.environment') === 'sandbox') {
            MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
        }
    }

    /**
     * Crear preferencia de pago para Checkout Pro
     */
    public function createPreference()
    {
        try {
            // Obtener datos del checkout desde la sesión
            $checkoutData = session()->get('checkout_data');
            
            if (!$checkoutData) {
                return response()->json([
                    'success' => false,
                    'error' => 'No hay datos de checkout'
                ], 400);
            }

            // Crear la orden ahora que el usuario va a pagar
            $order = Order::create([
                'order_number' => 'TEMP',
                'customer_name' => $checkoutData['customer_name'],
                'customer_email' => $checkoutData['customer_email'],
                'customer_phone' => $checkoutData['customer_phone'],
                'delivery_address' => $checkoutData['delivery_address'],
                'delivery_date' => $checkoutData['delivery_date'],
                'delivery_time' => null,
                'subtotal' => $checkoutData['subtotal'],
                'tax' => 0,
                'delivery_fee' => 0,
                'total' => $checkoutData['subtotal'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $checkoutData['notes'] ?? null
            ]);

            // Generar order_number
            $orderNumber = date('m') . str_pad($order->id, 7, '0', STR_PAD_LEFT);
            $order->update(['order_number' => $orderNumber]);

            // Crear los items de la orden
            foreach ($checkoutData['order_items'] as $itemData) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_name' => $itemData['product_name'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal']
                ]);
            }

            // Recargar la orden con sus items
            $order->load('items');

            // Enviar email de pedido creado (pendiente)
            \App\Jobs\SendOrderCreatedEmail::dispatch($order);

            // Verificar que el access token esté configurado
            $accessToken = config('services.mercadopago.access_token');
            if (empty($accessToken)) {
                Log::error('MercadoPago Access Token no configurado');
                return response()->json([
                    'success' => false,
                    'error' => 'Error de configuración: credenciales no disponibles'
                ], 500);
            }

            // Calcular el total de la orden
            $totalAmount = $order->items->sum(function($item) {
                return $item->subtotal;
            });

            // Crear un único item consolidado (simplifica validación de MP)
            $consolidatedItem = [
                'id' => 'ORDER-' . $order->id,
                'title' => 'Pedido de Catering #' . $order->order_number,
                'description' => 'Pedido de ' . $order->items->count() . ' producto(s)',
                'quantity' => 1,
                'unit_price' => round($totalAmount, 2),
                'currency_id' => 'ARS',
            ];

            $client = new PreferenceClient();

            // Construir URLs completas
            $baseUrl = config('app.url');
            
            // Crear request de preferencia - ESTRUCTURA SIMPLIFICADA
            $preferenceRequest = [
                'items' => [$consolidatedItem],
                'back_urls' => [
                    'success' => $baseUrl . '/payment/success',
                    'failure' => $baseUrl . '/payment/failure',
                    'pending' => $baseUrl . '/payment/pending',
                ],
                'statement_descriptor' => 'CATERING',
                'external_reference' => $order->order_number,
            ];

            // Agregar payer con datos completos (OBLIGATORIO para producción)
            // En sandbox, mejor omitirlo para permitir ingreso manual de datos de prueba
            $isSandbox = config('services.mercadopago.environment') === 'sandbox';
            
            if (!$isSandbox && !empty($order->customer_email) && filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
                // Solo en producción enviamos los datos del cliente
                $nameParts = explode(' ', trim($order->customer_name), 2);
                $firstName = $nameParts[0] ?? 'Cliente';
                $lastName = $nameParts[1] ?? 'Catering';
                
                $preferenceRequest['payer'] = [
                    'name' => $firstName,
                    'surname' => $lastName,
                    'email' => $order->customer_email,
                ];
                
                if (!empty($order->customer_phone)) {
                    $preferenceRequest['payer']['phone'] = [
                        'number' => preg_replace('/[^0-9]/', '', $order->customer_phone)
                    ];
                }
                
                Log::info('✅ Payer configurado (producción)');
            } else if ($isSandbox) {
                Log::info('⚠️ Sandbox: Payer omitido - ingresa manualmente los datos de prueba');
                Log::info('📝 Email de prueba: test_user_XXXXXXXX@testuser.com');
                Log::info('💳 Tarjeta VISA: 4509 9535 6623 3704 | CVV: 123 | Venc: 11/25');
            }

            // Agregar metadata
            $preferenceRequest['metadata'] = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'delivery_date' => $order->delivery_date,
            ];

            // Solo agregar notification_url si NO es localhost
            $isLocalhost = str_contains($baseUrl, 'localhost') || 
                          str_contains($baseUrl, '127.0.0.1');
            
            if (!$isLocalhost) {
                $preferenceRequest['notification_url'] = $baseUrl . '/mercadopago/webhook';
                Log::info('✅ Webhook configurado:', ['url' => $preferenceRequest['notification_url']]);
            } else {
                Log::info('⚠️ Desarrollo local: Webhook omitido (requiere URL pública)');
            }

            Log::info('Creando preferencia con datos:', $preferenceRequest);

            $preference = $client->create($preferenceRequest);

            Log::info('Respuesta de MP:', [
                'id' => $preference->id,
                'init_point' => $preference->init_point ?? $preference->sandbox_init_point,
            ]);

            // Guardar preference_id en la orden
            $order->update([
                'mercadopago_preference_id' => $preference->id,
                'payment_method' => 'mercadopago'
            ]);

            // Priorizar init_point (producción) sobre sandbox_init_point
            $checkoutUrl = $preference->init_point ?? $preference->sandbox_init_point;

            if (!$checkoutUrl) {
                throw new \Exception('No se pudo obtener URL de checkout');
            }

            return response()->json([
                'success' => true,
                'init_point' => $checkoutUrl,
                'preference_id' => $preference->id,
            ]);

        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();
            $content = $apiResponse->getContent();
            
            Log::error('MercadoPago API Error:', [
                'status_code' => $e->getStatusCode(),
                'response_content' => $content,
                'order_id' => $order->id
            ]);
            
            $errorMessage = $e->getMessage();
            
            // Intentar obtener un mensaje más específico
            $responseData = json_decode($content, true);
            if (is_array($responseData) && isset($responseData['message'])) {
                $errorMessage = $responseData['message'];
            }
            
            return response()->json([
                'success' => false,
                'error' => 'Error al crear la preferencia de pago: ' . $errorMessage,
                'details' => $responseData ?? $content
            ], 500);

        } catch (\Exception $e) {
            Log::error('MercadoPago General Error: ' . $e->getMessage(), [
                'order_id' => $order->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error inesperado al procesar el pago',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook de notificaciones de Mercado Pago
     */
    public function webhook(Request $request)
    {
        try {
            Log::info('MercadoPago Webhook recibido', [
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ]);

            $type = $request->input('type');
            $dataId = $request->input('data.id');

            // Solo procesar notificaciones de pagos
            if ($type === 'payment') {
                $this->processPaymentNotification($dataId);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Error en webhook de MercadoPago: ' . $e->getMessage());
            // Devolver 200 para evitar reintentos innecesarios
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    /**
     * Procesar notificación de pago usando el SDK oficial
     */
    private function processPaymentNotification($paymentId)
    {
        try {
            Log::info('Procesando notificación de pago', ['payment_id' => $paymentId]);

            // Obtener información del pago usando el SDK oficial
            $paymentClient = new PaymentClient();
            $payment = $paymentClient->get($paymentId);

            Log::info('Datos del pago obtenidos', [
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'external_reference' => $payment->external_reference
            ]);

            $externalReference = $payment->external_reference;

            if (!$externalReference) {
                Log::warning('External reference no encontrada en el pago');
                return;
            }

            // Buscar la orden por el order_number con sus items
            $order = Order::with('items')->where('order_number', $externalReference)->first();

            if (!$order) {
                Log::warning("Orden no encontrada para external_reference: {$externalReference}");
                return;
            }

            // Actualizar el estado del pago según el status de Mercado Pago
            $paymentStatus = match($payment->status) {
                'approved' => 'paid',
                'rejected', 'cancelled' => 'failed',
                'in_process', 'pending', 'authorized' => 'pending',
                'refunded' => 'refunded',
                default => 'pending'
            };

            $order->update([
                'mercadopago_payment_id' => $paymentId,
                'payment_status' => $paymentStatus,
                'paid_at' => $payment->status === 'approved' ? now() : null,
                'status' => $payment->status === 'approved' ? 'confirmed' : $order->status
            ]);

            // Enviar email si el pago fue aprobado
            if ($payment->status === 'approved') {
                \App\Jobs\SendOrderApprovedEmail::dispatch($order);
            }

            Log::info("Pago procesado exitosamente para orden {$order->order_number}", [
                'payment_id' => $paymentId,
                'status' => $payment->status,
                'payment_status' => $paymentStatus,
                'order_status' => $order->status
            ]);

        } catch (\Exception $e) {
            Log::error("Error al procesar notificación de pago {$paymentId}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Página de éxito del pago
     */
    public function success(Request $request)
    {
        Log::info('Payment Success - Parámetros recibidos', [
            'query' => $request->query(),
            'all' => $request->all()
        ]);

        $preferenceId = $request->query('preference_id');
        $paymentId = $request->query('payment_id');
        $externalReference = $request->query('external_reference');

        $order = Order::where('order_number', $externalReference)->first();
        
        Log::info('Payment Success - Orden encontrada', [
            'order_id' => $order ? $order->id : null,
            'order_number' => $order ? $order->order_number : null
        ]);

        // Limpiar el carrito y los datos de checkout de la sesión
        session()->forget(['cart', 'checkout_data']);

        return view('payment.success', compact('order', 'paymentId'));
    }

    /**
     * Página de pago fallido
     */
    public function failure(Request $request)
    {
        $externalReference = $request->query('external_reference');
        $order = Order::where('order_number', $externalReference)->first();

        // Restaurar el carrito desde la orden para que el usuario pueda continuar comprando
        if ($order && $order->items->count() > 0) {
            $cart = [];
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $cart[$item->product_id] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'product' => $product
                    ];
                }
            }
            session()->put('cart', $cart);
            
            // Opcional: Eliminar la orden fallida para evitar duplicados
            // $order->delete();
        }

        return view('payment.failure', compact('order'));
    }

    /**
     * Página de pago pendiente
     */
    public function pending(Request $request)
    {
        $externalReference = $request->query('external_reference');
        $order = Order::where('order_number', $externalReference)->first();

        // Restaurar el carrito desde la orden para pagos pendientes
        if ($order && $order->items->count() > 0) {
            $cart = [];
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $cart[$item->product_id] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'product' => $product
                    ];
                }
            }
            session()->put('cart', $cart);
        }

        return view('payment.pending', compact('order'));
    }
}
