<?php

namespace App\Http\Controllers;

use App\Models\OutboundItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OutboundItemController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['outbound_id']);
        $query = OutboundItem::query()->with('product')
            ->filter($filters);
        return DataTables::eloquent($query)->addIndexColumn()->toJson();
    }

    public function show(OutboundItem $outbound_item)
    {
        return response()->json(['data' => $outbound_item->load(['product', 'outbound'])]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'outbound_id'   => 'required|exists:outbounds,id',
            'product_id'    => 'required|exists:products,id',
            'qty'           => 'required|integer|gte:1',
        ]);

        $exist = OutboundItem::query()
            ->where('outbound_id', $request->outbound_id)
            ->where('product_id', $request->product_id)
            ->first();
        if ($exist) {
            return response()->json(['message' => 'Product Exist!'], 400);
        }

        $product = Product::query()
            ->withSum('trx as in', 'qty')
            ->withSum('purchase_items as qty_ord', 'qty_ord')
            ->withSum('out as out', 'qty')
            ->find($request->product_id);
        if ($product->outOffStock()) {
            return response()->json(['message' => 'Out Off Stock!'], 400);
        }
        if ($product->notEnoughStock($request->qty)) {
            return response()->json(['message' => 'Stock tidak mencukupi, sisa : ' . $product->stock . '!'], 400);
        }
        OutboundItem::create([
            'outbound_id'   => $request->outbound_id,
            'product_id'    => $request->product_id,
            'qty'           => $request->qty,
        ]);
        return response()->json(['message' => 'Data Inserted!', $product]);
    }

    public function update(Request $request, OutboundItem $outbound_item)
    {
        $this->validate($request, [
            'outbound_id'   => 'required|exists:outbounds,id',
            'product_id'    => 'required|exists:products,id',
            'qty'           => 'required|integer|gte:1',
        ]);

        // ambil product dengan stoknya
        $product = Product::query()
            ->withSum('trx as in', 'qty')
            ->withSum('purchase_items as qty_ord', 'qty_ord')
            ->withSum('out as out', 'qty')
            ->find($request->product_id);

        // hitung stok tersedia dengan menambahkan qty lama kembali
        $availableStock = $product->stock + $outbound_item->qty;

        if ($availableStock < 1) {
            return response()->json(['message' => 'Out of Stock!'], 400);
        }

        if ($availableStock < $request->qty) {
            return response()->json(['message' => 'Stock tidak mencukupi, sisa : ' . $availableStock . '!'], 400);
        }

        $outbound_item->update([
            'outbound_id'   => $request->outbound_id,
            'product_id'    => $request->product_id,
            'qty'           => $request->qty,
        ]);

        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(OutboundItem $outbound_item)
    {
        $outbound_item->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
