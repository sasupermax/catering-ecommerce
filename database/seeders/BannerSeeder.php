<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Catering Premium para tus Eventos',
            'image' => null, // Agregar imagen manualmente desde el panel
            'url' => null,
            'is_active' => true,
            'display_order' => 1,
            'transition_duration' => 5000,
            'start_date' => null,
            'end_date' => null,
        ]);

        Banner::create([
            'title' => 'Ofertas Especiales del Mes',
            'image' => null,
            'url' => null,
            'is_active' => true,
            'display_order' => 2,
            'transition_duration' => 6000,
            'start_date' => null,
            'end_date' => null,
        ]);

        Banner::create([
            'title' => 'Menú Completo para tu Celebración',
            'image' => null,
            'url' => null,
            'is_active' => true,
            'display_order' => 3,
            'transition_duration' => 5000,
            'start_date' => null,
            'end_date' => null,
        ]);
    }
}
