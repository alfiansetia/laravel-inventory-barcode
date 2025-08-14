<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseItemController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['purchase_id']);
        $query = PurchaseItem::query()->with('product')->withCount('barcodes')->filter($filters);
        return DataTables::eloquent($query)->toJson();
    }

    public function show(PurchaseItem $purchase_item)
    {
        return response()->json(['data' => $purchase_item->load(['product', 'barcodes', 'purchase'])]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'purchase_id'   => 'required|exists:purchases,id',
            'product_id'    => 'required|exists:products,id',
            'lot'           => 'required|integer|gte:1',
            'qty_kbn'       => 'required|integer|gte:1',
        ]);
        PurchaseItem::create([
            'purchase_id'   => $request->purchase_id,
            'product_id'    => $request->product_id,
            'lot'           => $request->lot,
            'qty_kbn'       => $request->qty_kbn,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, PurchaseItem $purchase_item)
    {
        $this->validate($request, [
            'purchase_id'   => 'required|exists:purchases,id',
            'product_id'    => 'required|exists:products,id',
            'lot'           => 'required|integer|gte:1',
            'qty_kbn'       => 'required|integer|gte:1',
        ]);
        $purchase_item->update([
            'purchase_id'   => $request->purchase_id,
            'product_id'    => $request->product_id,
            'lot'           => $request->lot,
            'qty_kbn'       => $request->qty_kbn,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(PurchaseItem $purchase_item)
    {
        $purchase_item->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
