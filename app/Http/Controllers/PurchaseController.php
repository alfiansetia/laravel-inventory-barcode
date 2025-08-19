<?php

namespace App\Http\Controllers;

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
            $file = $request->file('file');
            $data = Excel::toCollection([], $file)[0]->skip(1);
            foreach ($data as $index => $row) {
                if ($row->count() < 16) {
                    throw new Exception('Jumlah kolom tidak sesuai di baris ke-' . ($index + 1));
                }
            }

            $grouped = $data->groupBy(function ($item) {
                return $item[2];
            });
            foreach ($grouped as $rows) {
                $first = $rows->first();
                $ven_id = $first[0] ?? null;
                $ven_name = $first[1] ?? null;
                $po_no = $first[2] ?? null;
                $dn_no = $first[3] ?? null;
                $rit = $first[4] ?? null;
                $delv_date = ($first[5] ?? '') . ' ' . ($first[6] ?? '');

                if (empty($ven_id) || empty($ven_name) || empty($po_no) || empty($dn_no) || empty($rit)) {
                    throw new Exception('Data Dalam File Tidak Valid');
                }
                $vendor = Vendor::firstOrCreate([
                    'vendor_id' => $ven_id,
                ], [
                    'name' => $ven_name,
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
                    'dn_no'     => $dn_no,
                    'rit'       => $rit,
                    'delv_date' => $delv_date,
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
                        'lot'         => $row[11] ?? 0,
                        'qty_kbn'     => $row[12] ?? 0,
                        'qty_ord'     => $row[13] ?? 0,
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
