<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();               
            $table->string('tripay_reference');
            $table->string('buyer_email');
            $table->string('buyer_phone');
            $table->json('raw_response');          
            $table->timestamps();               
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
