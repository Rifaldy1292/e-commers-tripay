<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   
    public function index()
    {
        return response()->json(
            Product::with('invoices')->latest()->get(),
            200
        );
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'       => 'required|unique:products,sku',
            'name'      => 'required|string',
            'price'     => 'required|integer',
            'reference' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('invoices'), 201);
    }

   
    public function show(Product $product)
    {
        return response()->json($product->load('invoices'), 200);
    }

    
 
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku'       => 'required|unique:products,sku,' . $product->id,
            'name'      => 'required|string',
            'price'     => 'required|integer',
            'reference' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product->load('invoices'), 200);
    }

 
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
