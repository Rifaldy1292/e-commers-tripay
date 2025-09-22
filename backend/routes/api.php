<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CheckoutController;

Route::apiResource('products', ProductController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::post('/checkout', [CheckoutController::class, 'store']);
Route::get('/checkout/{id}', [CheckoutController::class, 'show']);
