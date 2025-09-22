<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Invoice;
use App\Models\Product;

class CheckoutController extends Controller
{
   
    public function store(Request $request)
    {
      
        $request->validate([
            'productId' => 'required|integer',
            'method' => 'required|string',
            'buyerEmail' => 'required|email',
            'buyerPhone' => 'required|string',
            'note' => 'nullable|string',
        ]);

        
        $product = Product::find($request->productId);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        
        $merchantCode = config('services.tripay.merchant_code');
        $privateKey   = config('services.tripay.private_key');
        $amount       = $product->price;
        $merchantRef  = uniqid('INV-');

       
        $signature = hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);

       
        $payload = [
            'method'         => $request->method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $request->buyerEmail, 
            'customer_email' => $request->buyerEmail,
            'customer_phone' => $request->buyerPhone,
            'order_items'    => [
                [
                    'sku'        => $product->sku ?? '',
                    'name'       => $product->name,
                    'price'      => $amount,
                    'quantity'   => 1,
                    'product_url'=> '', 
                    'image_url'  => '', 
                ]
            ],
            'callback_url'   => config('services.tripay.callback_url'),
            'return_url'     => config('services.tripay.return_url'),
            'expired_time'   => now()->addHours(6)->timestamp,
            'signature'      => $signature,
            'note'           => $request->note,
        ];

        try {
            
            $tripayRes = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.tripay.api_key'),
                    'Accept' => 'application/json',
                ])
                ->post(config('services.tripay.api_url'), $payload)
                ->json();
        } catch (\Exception $e) {
            
            return response()->json([
                'message' => 'Gagal menghubungi Tripay',
                'details' => $e->getMessage(),
            ], 500);
        }

        if (empty($tripayRes['success']) || !$tripayRes['success']) {
            return response()->json([
                'message' => 'Gagal membuat invoice Tripay',
                'details' => $tripayRes
            ], 422);
        }

        
        $invoice = Invoice::create([
            'product_id'       => $product->id,
            'tripay_reference' => $tripayRes['data']['reference'] ?? null,
            'buyer_email'      => $request->buyerEmail,
            'buyer_phone'      => $request->buyerPhone,
            'amount'           => $amount,
            'status'           => $tripayRes['data']['status'] ?? 'PENDING',
            'raw_response'     => json_encode($tripayRes),
        ]);

        return response()->json([
            'data' => [
                'id'             => $invoice->id,
                'amount'         => $invoice->amount,
                'status'         => $invoice->status,
                'tripayReference'=> $invoice->tripay_reference,
                'checkoutUrl'    => $tripayRes['data']['checkout_url'] ?? null,
                'createdAt'      => $invoice->created_at,
                'updatedAt'      => $invoice->updated_at,
            ]
        ]);
    }

   
    public function show($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return response()->json(['message' => 'Invoice tidak ditemukan'], 404);
        }
        return response()->json([
            'data' => [
                'id'             => $invoice->id,
                'amount'         => $invoice->amount,
                'status'         => $invoice->status,
                'tripayReference'=> $invoice->tripay_reference,
                'checkoutUrl'    => $invoice->raw_response['data']['checkout_url'] ?? null,
                'createdAt'      => $invoice->created_at,
                'updatedAt'      => $invoice->updated_at,
            ]
        ]);
    }
}
