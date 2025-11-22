<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Información general
            ['key' => 'site_name', 'value' => 'Catering Delicioso', 'type' => 'text'],
            ['key' => 'site_email', 'value' => 'contacto@catering.com', 'type' => 'text'],
            ['key' => 'site_phone', 'value' => '+54 11 1234-5678', 'type' => 'text'],
            ['key' => 'site_address', 'value' => 'Av. Corrientes 1234, CABA, Buenos Aires, Argentina', 'type' => 'textarea'],
            
            // Configuración de pedidos
            ['key' => 'max_orders_per_day', 'value' => '10', 'type' => 'number'],
            ['key' => 'min_days_advance', 'value' => '2', 'type' => 'number'],
            ['key' => 'max_days_advance', 'value' => '30', 'type' => 'number'],
            
            // Configuración de ventas
            ['key' => 'minimum_order_amount', 'value' => '1000', 'type' => 'number'],
            
            // Configuración del carousel
            ['key' => 'carousel_transition_duration', 'value' => '5000', 'type' => 'integer'],
            
            // Estado del sitio
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            
            // Mercado Pago (vacío por defecto)
            ['key' => 'mercadopago_public_key', 'value' => '', 'type' => 'text'],
            ['key' => 'mercadopago_access_token', 'value' => '', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
