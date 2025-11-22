<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderCreatedEmail implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->order->customer_email)
                ->send(new OrderCreated($this->order));
            
            Log::info('Email de pedido creado enviado', [
                'order_id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'email' => $this->order->customer_email
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar email de pedido creado', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
