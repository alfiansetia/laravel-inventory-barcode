<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['purchase_item_id']);
        $query = Barcode::query()->with('purchase_item')->filter($filters);
        return DataTables::eloquent($query)->toJson();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'purchase_item_id'  => 'required|exists:purchase_items,id',
            'product_id'        => 'required|exists:products,id',
            'barcode'           => 'required|max:100|unique:barcodes,barcode',
        ]);
        Barcode::create([
            'purchase_item_id'  => $request->purchase_item_id,
            'product_id'        => $request->product_id,
            'barcode'           => $request->barcode,
            'qty'               => 1,
            'input_date'        => now(),
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }


    public function update(Request $request, Barcode $barcode)
    {
        $this->validate($request, [
            'purchase_item_id'  => 'required|exists:purchase_items,id',
            'product_id'        => 'required|exists:products,id',
            'barcode'           => 'required|max:100',
        ]);
        $barcode->update([
            'purchase_item_id'  => $request->purchase_item_id,
            'barcode'           => $request->barcode,
            'product_id'        => $request->product_id,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function destroy(Barcode $barcode)
    {
        $barcode->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }


    public function scan()
    {
        return view('barcode.scan');
    }

    public function get(Request $request)
    {
        try {
            if (!$request->filled('barcode')) {
                throw new Exception('Data Barcode Tidak Ditemukan!');
            }
            $barcode = $request->barcode;
            $exist = Barcode::where('barcode', $barcode)->first();
            if ($exist) {
                throw new Exception('Data Barcode Sudah ada!');
            }
            try {
                list($productCode, $poRit, $qtyKbn) = explode('+', $barcode);
                list($poNo, $rit) = explode('-', $poRit);
            } catch (\Throwable $th) {
                throw new Exception('Barcode Tidak Valid!');
            }

            $purchaseItem = PurchaseItem::query()->with(['purchase.vendor', 'product'])
                ->whereRelation('purchase', 'po_no', $poNo)
                ->whereRelation('purchase', 'rit', $rit)
                ->whereHas('product', function ($q) use ($productCode) {
                    $q->where('code', $productCode);
                })
                ->where('qty_kbn', '>=', $qtyKbn)
                ->first();

            if (!$purchaseItem) {
                throw new Exception('Purchase item not found!');
            }

            return response()->json(['data' => $purchaseItem]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 400);
        }
    }
}
