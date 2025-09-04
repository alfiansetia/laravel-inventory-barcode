<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
            'satuan'    => 'required|max:10',
        ]);
        Product::create([
            'name'      => $request->name,
            'code'      => $request->code,
            'satuan'    => $request->satuan,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'code'      => 'required|max:100|unique:products,code,' . $product->id,
            'satuan'    => 'required|max:10',
        ]);
        $param = [
            'name'      => $request->name,
            'code'      => $request->code,
            'satuan'    => $request->satuan,
        ];
        $product->update($param);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $data = Excel::toCollection([], $file)[0]->skip(1);

            foreach ($data as $index => $row) {
                $code = $row[0];
                $name     = $row[1];
                $satuan  = $row[2];
                // cek apakah sudah ada
                $exists = Product::where('code', $code)
                    ->exists();
                if ($exists) {
                    throw new \Exception("Data duplikat ditemukan di baris " . ($index + 2) .
                        " (code: $code / name: $name)");
                }
                Product::create([
                    'code'      => $code,
                    'name'      => $name,
                    'satuann'   => $satuan,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'success Import']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal import: ' . $th->getMessage()], 500);
        }
    }


    public function history(Request $request, Product $product)
    {
        if ($request->ajax()) {
            $in = $product->trx()->get()->map(function ($item) {
                return [
                    'type' => 'in',           // kasih label
                    'date' => $item->date,    // tanggal masuk
                    'qty'  => $item->qty,
                    'reff' => $item->purchase_item->purchase->po_no ?? null, // kalau ada field lain
                    'data' => $item,
                ];
            });
            $out = $product->out()->get()->map(function ($item) {
                return [
                    'type' => 'out',
                    'date' => $item->outbound->date ?? null,
                    'qty'  => $item->qty,
                    'reff' => $item->outbound->number ?? null,
                    'data' => $item,
                ];
            });
            $history = $in->concat($out)->sortBy('date')->values();
            return response()->json([
                'data' => $history
            ]);
        }
        $data = $product;
        return view('product.history', compact('data'));
    }
}
