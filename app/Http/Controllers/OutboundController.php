<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OutboundController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Outbound::query()->withCount(['items']);
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        return view('outbound.index');
    }

    public function show(Request $request, Outbound $outbound)
    {
        $data = $outbound->load(['items.product']);
        if ($request->ajax()) {
            return response()->json(['data' => $data]);
        }
        $products = Product::all();
        return view('outbound.show', compact('data', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'number'    => 'required|unique:outbounds,number',
            'date'      => 'required|date_format:Y-m-d H:i:s',
            'desc'      => 'nullable|max:200',
        ]);
        Outbound::create([
            'number'    => $request->number,
            'date'      => $request->date,
            'desc'      => $request->desc,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Outbound $outbound)
    {
        $this->validate($request, [
            'number'    => 'required|unique:outbounds,number,' . $outbound->id,
            'date'      => 'required|date_format:Y-m-d H:i:s',
            'desc'      => 'nullable|max:200',
        ]);
        $outbound->update([
            'number'    => $request->number,
            'date'      => $request->date,
            'desc'      => $request->desc,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Outbound $outbound)
    {
        $outbound->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
