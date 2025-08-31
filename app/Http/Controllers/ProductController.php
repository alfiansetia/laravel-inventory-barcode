<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $query = Product::query()->withSum('barcodes as stock', 'qty');
            $query = Product::query()
                ->withSum('trx as in', 'qty')
                ->withSum('purchase_items as qty_ord', 'qty_ord')
                ->withSum('out as out', 'qty');
            return DataTables::eloquent($query)
                ->addColumn('stock', function ($row) {
                    return (intval($row->in) - intval($row->out)) ?? 0;
                })->addColumn('outstanding', function ($row) {
                    return (intval($row->qty_ord) - intval($row->in)) ?? 0;
                })->editColumn('qty_ord', function ($row) {
                    return intval($row->qty_ord) ?? 0;
                })->addIndexColumn()->toJson();
        }
        return view('product.index');
    }

    public function show(Product $product)
    {
        return response()->json(['data' => $product]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'code'      => 'required|string|max:100|unique:products,code',
            'desc'      => 'nullable|max:200',
        ]);
        Product::create([
            'name'      => $request->name,
            'code'      => $request->code,
            'desc'      => $request->desc,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'code'      => 'required|max:100|unique:products,code,' . $product->id,
            'desc'      => 'nullable|max:200',
        ]);
        $param = [
            'name'      => $request->name,
            'code'      => $request->code,
            'desc'      => $request->desc,
        ];
        $product->update($param);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }


    public function history(Request $request, Product $product)
    {
        if ($request->ajax()) {
            // 
        }
        $data = $product;
        return view('product.history', compact('data'));
    }
}
