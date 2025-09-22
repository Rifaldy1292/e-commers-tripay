<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Product;

class InvoicesTableSeeder extends Seeder
{
    public function run(): void
    {
   
        $product = Product::first();

        if ($product) {
            Invoice::create([
                'product_id'       => $product->id,
                'tripay_reference' => 'TRIPAY-INV-001',
                'buyer_email'      => 'buyer@example.com',
                'buyer_phone'      => '081234567890',
                'raw_response'     => json_encode([
                    'status' => 'paid',
                    'amount' => 150000,
                ]),
            ]);
        }
    }
}
