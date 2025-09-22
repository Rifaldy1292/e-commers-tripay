<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;

Route::apiResource('products', ProductController::class);
Route::apiResource('invoices', InvoiceController::class);
