<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'sku'       => 'PRD-001',
            'name'      => 'Produk Contoh A',
            'price'     => 150000,
            'reference' => 'REF-TRIPAY-001',
        ]);

        Product::create([
            'sku'       => 'PRD-002',
            'name'      => 'Produk Contoh B',
            'price'     => 250000,
            'reference' => 'REF-TRIPAY-002',
        ]);
    }
}
