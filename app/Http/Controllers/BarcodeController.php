<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['purchase_item_id']);
        $query = Barcode::query()->with('product')->filter($filters);
        return DataTables::eloquent($query)->toJson();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'purchase_item_id'  => 'required|exists:purchase_items,id',
            'barcode'           => 'required|max:100|unique:barcodes,barcode',
        ]);
        Barcode::create([
            'purchase_item_id'  => $request->purchase_item_id,
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
            'barcode'           => 'required|max:100',
        ]);
        $barcode->update([
            'purchase_item_id'  => $request->purchase_item_id,
            'barcode'           => $request->barcode,
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
        if (!$request->filled('barcode')) {
            return response()->json(['message' => 'Data Barcode Tidak Ditemukan!'], 404);
        }
        // $data = PurchaseItem::query()->with(['purchase.vendor', 'product'])->first();
        $barcode = $request->barcode;
        // return response()->json(['message' => $barcode], 404);

        list($productCode, $poRit, $qtyKbn) = explode('+', $barcode);

        // Pisahkan po_no dan rit dari "76862-1"
        list($poNo, $rit) = explode('-', $poRit);
        // return response()->json(['x' => [$productCode, $poRit, $qtyKbn, $poNo, $rit]], 404);

        // Ambil purchase sesuai PO dan rit
        $purchase = Purchase::where('po_no', $poNo)
            ->where('rit', $rit)
            ->first();
        // return response()->json($purchase, 404);

        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        // Ambil purchase item sesuai product.code dan qty_kbn
        $purchaseItem = PurchaseItem::query()->with(['purchase.vendor', 'product'])
            // ->where('purchase_id', $purchase->id)
            ->whereRelation('purchase', 'po_no', $poNo)
            ->whereRelation('purchase', 'rit', $rit)
            ->whereHas('product', function ($q) use ($productCode) {
                $q->where('code', $productCode);
            })
            // ->where('qty_kbn', $qtyKbn)
            ->first();

        if (!$purchaseItem) {
            return response()->json([
                'message' => 'Purchase item not found',
                'x' => $purchaseItem,
                'y' => $purchase,
            ], 404);
        }

        // return response()->json([
        //     'purchase' => $purchase,
        //     'purchase_item' => $purchaseItem
        // ]);
        return response()->json(['data' => $purchaseItem]);
    }
}
