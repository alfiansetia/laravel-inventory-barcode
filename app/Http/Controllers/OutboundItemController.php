<?php

namespace App\Http\Controllers;

use App\Models\OutboundItem;
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
        OutboundItem::create([
            'outbound_id'   => $request->outbound_id,
            'product_id'    => $request->product_id,
            'qty'           => $request->qty,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, OutboundItem $outbound_item)
    {
        $this->validate($request, [
            'outbound_id'   => 'required|exists:outbounds,id',
            'product_id'    => 'required|exists:products,id',
            'qty'           => 'required|integer|gte:1',
        ]);
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
