<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Tampilkan semua invoice (beserta relasi product).
     * GET /api/invoices
     */
    public function index()
    {
        return response()->json(
            Invoice::with('product')->latest()->get(),
            200
        );
    }

    /**
     * Simpan invoice baru.
     * POST /api/invoices
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'tripay_reference' => 'required|string',
            'buyer_email'      => 'required|email',
            'buyer_phone'      => 'required|string',
            'raw_response'     => 'required|json',
        ]);

        $invoice = Invoice::create($validated);

        return response()->json($invoice->load('product'), 201);
    }

    /**
     * Detail invoice tertentu.
     * GET /api/invoices/{invoice}
     */
    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load('product'), 200);
    }

    /**
     * Update invoice.
     * PUT /api/invoices/{invoice}
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'product_id'       => 'sometimes|exists:products,id',
            'tripay_reference' => 'sometimes|string',
            'buyer_email'      => 'sometimes|email',
            'buyer_phone'      => 'sometimes|string',
            'raw_response'     => 'sometimes|json',
        ]);

        $invoice->update($validated);

        return response()->json($invoice->load('product'), 200);
    }

    /**
     * Hapus invoice.
     * DELETE /api/invoices/{invoice}
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
