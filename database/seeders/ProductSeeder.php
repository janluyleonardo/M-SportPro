<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Uniforme Oficial Titular',
                'description' => 'Camiseta y pantaloneta oficial del club, tela transpirable de alta calidad.',
                'price' => 85000,
                'stock' => 15,
            ],
            [
                'name' => 'Balón de Microfútbol Profesional',
                'description' => 'Balón oficial de competencia, talla y peso reglamentario.',
                'price' => 120000,
                'stock' => 10,
            ],
            [
                'name' => 'Medias Oficiales (Par)',
                'description' => 'Medias de alta compresión con logo del club.',
                'price' => 18000,
                'stock' => 40,
            ],
            [
                'name' => 'Canilleras Jackeline FS',
                'description' => 'Protección ergonómica con diseño personalizado.',
                'price' => 35000,
                'stock' => 20,
            ],
            [
                'name' => 'Chaqueta Rompevientos Club',
                'description' => 'Ideal para entrenamientos en climas fríos, material impermeable.',
                'price' => 95000,
                'stock' => 8,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }
}
