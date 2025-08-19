<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Http\Request;

class PurchaseScanController extends Controller
{
    public function index(Request $request, Purchase $purchase)
    {
        if ($request->ajax()) {
            return $this->get($request, $purchase);
        }
        $data = $purchase->load(['vendor', 'items.product']);
        return view('purchase.scan', compact('data'));
    }

    public function save(Request $request)
    {
        // 
    }

    public function get(Request $request, Purchase $purchase)
    {
        try {
            if (!$request->filled('barcode')) {
                throw new Exception('Tidak Ditemukan!');
            }
            $barcode = $request->barcode;
            $state = false;
            $exist = Barcode::where('barcode', $barcode)->first();
            if ($exist) {
                throw new Exception('Sudah Tersimpan!');
            }
            $pattern = '/^[A-Z0-9\-]+\+\d+(?:-\d+)?\+\d+$/i';

            if (!preg_match($pattern, $barcode)) {
                throw new Exception('Tidak Valid!');
            }
            try {
                list($productCode, $poRit, $qtyKbn) = array_map('trim', explode('+', $barcode));
                list($poNo, $rit) = array_map('trim', explode('-', $poRit));
                // list($poNo, $rit) = array_pad(array_map('trim', explode('-', $poRit)), 2, null);
            } catch (\Throwable $th) {
                throw new Exception('Tidak Valid!');
            }

            if ($qtyKbn < 1) {
                throw new Exception('Tidak Valid!');
            }
            // return response()->json($purchase);

            $purchaseItem = PurchaseItem::query()->with(['purchase.vendor', 'product'])
                ->whereRelation('purchase', 'status', 'open')
                ->where('purchase_id', $purchase->id)
                ->whereRelation('purchase', 'po_no', $poNo)
                ->whereRelation('product', 'code', $productCode)
                ->where('qty_kbn', '>=', $qtyKbn)
                ->first();
            // $purchaseItem = $purchase
            //     ->items()
            //     ->with(['purchase.vendor', 'product'])
            //     ->whereRelation('product', 'code', $productCode)
            //     ->where('qty_kbn', '>=', $qtyKbn)
            //     ->first();

            if (!$purchaseItem) {
                throw new Exception('Item Tidak Ditemukan!');
            }

            return response()->json([
                'data'  => $purchaseItem
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Barcode ' . $barcode . ' : ' . $e->getMessage()], 400);
        }
    }
}
