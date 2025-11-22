<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos productos para vincular a las ofertas
        $products = Product::take(5)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No hay productos en la base de datos. Crea productos primero.');
            return;
        }

        // Oferta 1: 20% de descuento en productos seleccionados
        $offer1 = Offer::create([
            'name' => 'Descuento del 20% en Platos Principales',
            'description' => 'Aprovecha un 20% de descuento en nuestros platos principales más populares. Oferta válida por tiempo limitado.',
            'discount_type' => 'percentage',
            'discount_value' => 20.00,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(15),
            'is_active' => true,
            'min_purchase_amount' => 100.00,
        ]);
        $offer1->products()->attach($products->take(2)->pluck('id'));

        // Oferta 2: Descuento fijo de $50 en catering
        $offer2 = Offer::create([
            'name' => 'Descuento $50 en Pedidos de Catering',
            'description' => 'Obtén $50 de descuento en pedidos de catering superiores a $500. Ideal para eventos corporativos y familiares.',
            'discount_type' => 'fixed',
            'discount_value' => 50.00,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(30),
            'is_active' => true,
            'min_purchase_amount' => 500.00,
        ]);
        $offer2->products()->attach($products->skip(2)->take(2)->pluck('id'));

        // Oferta 3: Oferta próxima (inactiva aún)
        $offer3 = Offer::create([
            'name' => 'Promoción Fin de Mes - 15% OFF',
            'description' => 'Prepárate para nuestra promoción de fin de mes. 15% de descuento en todos los productos seleccionados.',
            'discount_type' => 'percentage',
            'discount_value' => 15.00,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(35),
            'is_active' => true,
            'min_purchase_amount' => 200.00,
        ]);
        $offer3->products()->attach($products->skip(4)->take(1)->pluck('id'));

        $this->command->info('✅ Se crearon 3 ofertas de ejemplo exitosamente.');
    }
}
