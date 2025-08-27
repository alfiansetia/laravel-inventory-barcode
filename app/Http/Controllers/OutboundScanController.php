<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\OutboundItem;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class OutboundScanController extends Controller
{
    public function get(Request $request, Outbound $outbound)
    {
        try {
            if (!$request->filled('barcode')) {
                throw new Exception('Tidak Ditemukan!');
            }
            $barcode = $request->barcode;
            $parts = explode('+', $barcode);
            $productCode = $parts[0];

            $product = Product::query()
                ->withSum('trx as in', 'qty')
                ->withSum('purchase_items as qty_ord', 'qty_ord')
                ->withSum('out as out', 'qty')
                ->where('code', $productCode)
                ->first();
            if (!$product) {
                throw new Exception('Item Tidak Ditemukan!');
            }
            if ($product->outOffStock()) {
                throw new Exception('Out Off Stock!');
            }
            $exist = OutboundItem::query()
                ->where('outbound_id', $outbound->id)
                ->where('product_id', $product->id)
                ->first();
            if ($exist) {
                throw new Exception('Product Sudah ada di tabel!');
            }
            $outbound->items()->create([
                'product_id'    => $product->id,
                'qty'           => 1,
            ]);
            return response()->json([
                'data'      => $product,
                'message'   => 'Item berhasil ditambahkan!',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Barcode ' . $barcode . ' : ' . $e->getMessage()], 400);
        }
    }
}
