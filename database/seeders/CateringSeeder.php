<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class CateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Categorías
        $categories = [
            [
                'name' => 'Entradas',
                'slug' => 'entradas',
                'description' => 'Deliciosas entradas para comenzar tu evento',
                'is_active' => true
            ],
            [
                'name' => 'Platos Fuertes',
                'slug' => 'platos-fuertes',
                'description' => 'Los mejores platos principales para tu evento',
                'is_active' => true
            ],
            [
                'name' => 'Ensaladas',
                'slug' => 'ensaladas',
                'description' => 'Ensaladas frescas y saludables',
                'is_active' => true
            ],
            [
                'name' => 'Postres',
                'slug' => 'postres',
                'description' => 'Dulces delicias para finalizar',
                'is_active' => true
            ],
            [
                'name' => 'Bebidas',
                'slug' => 'bebidas',
                'description' => 'Refrescantes bebidas naturales',
                'is_active' => true
            ],
            [
                'name' => 'Paquetes',
                'slug' => 'paquetes',
                'description' => 'Paquetes completos para eventos',
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Crear Productos
        $products = [
            // Entradas
            ['category_id' => 1, 'name' => 'Brochetas de Pollo', 'slug' => 'brochetas-pollo', 'plu' => '1001', 'description' => 'Deliciosas brochetas de pollo marinado con vegetales', 'price' => 150.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            ['category_id' => 1, 'name' => 'Mini Empanadas', 'slug' => 'mini-empanadas', 'plu' => '1002', 'description' => 'Empanadas artesanales de carne y queso', 'price' => 120.00, 'type' => 'N', 'is_available' => true, 'min_order' => 20],
            ['category_id' => 1, 'name' => 'Dedos de Queso', 'slug' => 'dedos-queso', 'plu' => '1003', 'description' => 'Crujientes dedos de queso mozzarella', 'price' => 100.00, 'type' => 'N', 'is_available' => true, 'min_order' => 15],
            
            // Platos Fuertes
            ['category_id' => 2, 'name' => 'Pollo al Horno', 'slug' => 'pollo-horno', 'plu' => '2001', 'description' => 'Pollo rostizado con hierbas y especias', 'price' => 250.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            ['category_id' => 2, 'name' => 'Lasagna de Carne', 'slug' => 'lasagna-carne', 'plu' => '2002', 'description' => 'Lasagna casera con carne molida y quesos', 'price' => 200.00, 'type' => 'N', 'is_available' => true, 'min_order' => 8],
            ['category_id' => 2, 'name' => 'Pescado a la Veracruzana', 'slug' => 'pescado-veracruzana', 'plu' => '2003', 'description' => 'Filete de pescado en salsa de tomate con aceitunas', 'price' => 280.00, 'type' => 'P', 'is_available' => true, 'min_order' => 10],
            
            // Ensaladas
            ['category_id' => 3, 'name' => 'Ensalada César', 'slug' => 'ensalada-cesar', 'plu' => '3001', 'description' => 'Clásica ensalada césar con pollo y crutones', 'price' => 90.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            ['category_id' => 3, 'name' => 'Ensalada Mediterránea', 'slug' => 'ensalada-mediterranea', 'plu' => '3002', 'description' => 'Ensalada con queso feta, aceitunas y vegetales frescos', 'price' => 110.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            
            // Postres
            ['category_id' => 4, 'name' => 'Tiramisú', 'slug' => 'tiramisu', 'plu' => '4001', 'description' => 'Clásico postre italiano con café y mascarpone', 'price' => 80.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            ['category_id' => 4, 'name' => 'Cheesecake de Fresa', 'slug' => 'cheesecake-fresa', 'plu' => '4002', 'description' => 'Suave cheesecake con cobertura de fresas', 'price' => 85.00, 'type' => 'N', 'is_available' => true, 'min_order' => 10],
            ['category_id' => 4, 'name' => 'Brownies', 'slug' => 'brownies', 'plu' => '4003', 'description' => 'Brownies de chocolate con nueces', 'price' => 60.00, 'type' => 'N', 'is_available' => true, 'min_order' => 20],
            
            // Bebidas
            ['category_id' => 5, 'name' => 'Agua de Jamaica', 'slug' => 'agua-jamaica', 'plu' => '5001', 'description' => 'Refrescante agua de jamaica natural', 'price' => 40.00, 'type' => 'N', 'is_available' => true, 'min_order' => 20],
            ['category_id' => 5, 'name' => 'Limonada Natural', 'slug' => 'limonada-natural', 'plu' => '5002', 'description' => 'Limonada fresca con menta', 'price' => 45.00, 'type' => 'N', 'is_available' => true, 'min_order' => 20],
            
            // Paquetes
            ['category_id' => 6, 'name' => 'Paquete Ejecutivo', 'slug' => 'paquete-ejecutivo', 'plu' => '6001', 'description' => 'Incluye: entrada, plato fuerte, postre y bebida para 10 personas', 'price' => 3500.00, 'type' => 'N', 'is_available' => true, 'min_order' => 1],
            ['category_id' => 6, 'name' => 'Paquete Familiar', 'slug' => 'paquete-familiar', 'plu' => '6002', 'description' => 'Incluye: entrada, plato fuerte y bebida para 15 personas', 'price' => 4200.00, 'type' => 'N', 'is_available' => true, 'min_order' => 1],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
