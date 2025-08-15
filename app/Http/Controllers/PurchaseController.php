<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseImport;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Vendor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Purchase::query()->with('vendor')->withCount(['items']);
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        $vendors = Vendor::all();
        return view('purchase.index', compact('vendors'));
    }

    public function show(Request $request, Purchase $purchase)
    {
        $data = $purchase->load(['vendor', 'items.product']);
        if ($request->ajax()) {
            return response()->json(['data' => $data]);
        }
        $products = Product::all();
        return view('purchase.show', compact('data', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'vendor_id' => 'required|exists:vendors,id',
            'po_no'     => 'required|unique:purchases,po_no',
            'dn_no'     => 'required',
            'delv_date' => 'required|date_format:Y-m-d H:i:s',
            'rit'       => 'required|integer|gte:1',
            'status'    => 'required|in:open,close',
        ]);
        Purchase::create([
            'vendor_id' => $request->vendor_id,
            'po_no'     => $request->po_no,
            'dn_no'     => $request->dn_no,
            'delv_date' => $request->delv_date,
            'rit'       => $request->rit,
            'status'    => $request->status,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Purchase $purchase)
    {
        $this->validate($request, [
            'vendor_id' => 'required|exists:vendors,id',
            'po_no'     => 'required|unique:purchases,po_no,' . $purchase->id,
            'dn_no'     => 'required',
            'delv_date' => 'required|date_format:Y-m-d H:i:s',
            'rit'       => 'required|integer|gte:1',
            'status'    => 'required|in:open,close',
        ]);
        $purchase->update([
            'vendor_id' => $request->vendor_id,
            'po_no'     => $request->po_no,
            'dn_no'     => $request->dn_no,
            'delv_date' => $request->delv_date,
            'rit'       => $request->rit,
            'status'    => $request->status,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        DB::beginTransaction();
        try {
            $data = Excel::toCollection([], $request->file('file'))[0]->skip(1);
            $grouped = $data->groupBy(function ($item) {
                return $item[2];
            });
            foreach ($grouped as $rows) {
                $po_no =  $rows->first()[2];
                $vendor = Vendor::firstOrCreate([
                    'vendor_id' => $rows->first()[0],
                ], [
                    'name' => $rows->first()[1],
                ]);

                $exist = Purchase::query()
                    ->where('po_no', $po_no)
                    ->first();
                if ($exist) {
                    throw new Exception("Purchase $po_no Sudah ada!");
                }

                // Insert purchase
                $purchase = Purchase::create([
                    'vendor_id' => $vendor->id,
                    'po_no'     => $po_no,
                    'dn_no'     => $rows->first()[3],
                    'rit'       => $rows->first()[4],
                    'delv_date' => $rows->first()[5] . ' ' . $rows->first()[6],
                ]);

                // Siapkan purchase detail
                $details = $rows->map(function ($row) use ($purchase) {
                    $product = Product::firstOrCreate([
                        'code' => $row[9],
                    ], [
                        'name' => $row[10],
                    ]);
                    return [
                        'purchase_id' => $purchase->id,
                        'product_id'  => $product->id,
                        'lot'         => $row[11],
                        'qty_kbn'     => $row[12],
                    ];
                })->toArray();
                PurchaseItem::insert($details);
            }
            DB::commit();
            return response()->json($grouped);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal import: ' . $th->getMessage()], 500);
        }
    }
}
