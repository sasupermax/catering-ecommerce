<?php

namespace App\Console\Commands;

use App\Jobs\SendOrderCreatedEmail;
use App\Jobs\SendOrderApprovedEmail;
use App\Models\Order;
use Illuminate\Console\Command;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=created} {--order=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar email de prueba (created o approved)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $orderId = $this->option('order');

        // Obtener la orden
        if ($orderId) {
            $order = Order::find($orderId);
        } else {
            $order = Order::with('items')->latest()->first();
        }

        if (!$order) {
            $this->error('No se encontró ninguna orden. Crea una orden primero.');
            return 1;
        }

        $this->info("Enviando email de tipo '{$type}' para la orden #{$order->order_number}");
        $this->info("Email destino: {$order->customer_email}");

        try {
            if ($type === 'created') {
                SendOrderCreatedEmail::dispatch($order);
                $this->info('✅ Email de "Pedido Recibido" encolado exitosamente!');
            } elseif ($type === 'approved') {
                SendOrderApprovedEmail::dispatch($order);
                $this->info('✅ Email de "Pago Confirmado" encolado exitosamente!');
            } else {
                $this->error('Tipo inválido. Usa "created" o "approved"');
                return 1;
            }

            $this->warn('⚠️  Recuerda ejecutar: php artisan queue:work');
            $this->info('Para procesar los emails en cola.');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error al encolar el email: ' . $e->getMessage());
            return 1;
        }
    }
}
