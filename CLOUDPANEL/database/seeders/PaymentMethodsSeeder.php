<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentSettings = [
            ['key' => 'payment_mercadopago_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'payment_bank_transfer_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'payment_cash_enabled', 'value' => '1', 'type' => 'boolean']
        ];

        foreach ($paymentSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
