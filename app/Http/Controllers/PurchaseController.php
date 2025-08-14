<?php

namespace App\Http\Controllers;

use App\Imports\PurchaseImport;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Vendor;
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
            $query = Purchase::query()->with('vendor');
            return DataTables::eloquent($query)->toJson();
        }
        return view('purchase.index');
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $data = Excel::toCollection([], $request->file('file'))[0]->skip(1);
        // $collection = collect($data);
        $grouped = $data->groupBy(function ($item) {
            return $item[2]; // Kolom PO NO
        });
        foreach ($grouped as $rows) {
            // Pastikan vendor ada
            $vendor = Vendor::firstOrCreate([
                'vendor_id' => $rows->first()[0],
            ], [
                'name' => $rows->first()[1],
            ]);

            // Insert purchase
            $purchase = Purchase::create([
                'vendor_id' => $vendor->id,
                'po_no'     => $rows->first()[2],
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
                // for ($i = 1; $i <= $row[12]; $i++) {
                //     Barcode::create([
                //         'barcode'       => $purchase->po_no . '+' . $i,
                //         'qty'           => 1,
                //         'input_date'    => now(),
                //     ]);
                // }

                return [
                    'purchase_id' => $purchase->id,
                    'product_id'  => $product->id,
                    'lot'         => $row[11],
                    'qty_kbn'     => $row[12],
                ];
            })->toArray();

            // Insert batch
            PurchaseItem::insert($details);
        }
        return response()->json($grouped);
        // DB::beginTransaction();
        // try {
        //     Excel::toArray([], $request->file('file'))[0];
        //     DB::commit();
        //     return $this->response('Data berhasil diimport!');
        // } catch (ValidationException $e) {
        //     DB::rollBack();
        //     $failures = $e->failures();
        //     $messages = [];
        //     foreach ($failures as $failure) {
        //         $messages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
        //     }

        //     return response()->json([
        //         'message' => 'Gagal import!, ' . implode(', ', $messages),
        //         'errors' => $messages,
        //     ], 422);
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     return $this->response('Gagal import: ' . $th->getMessage(), [], 500);
        // }
    }
}
