<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $this->validate($request, [
            'purchase_item_id'      => 'required|array',
            'purchase_item_id.*'    => 'required|exists:purchase_items,id',
            'product_id'            => 'required|array',
            'product_id.*'          => 'required|exists:products,id',
            'name'                  => 'required|array',
            'name.*'                => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->purchase_item_id as $index => $pitemId) {
                $productId = $request->product_id[$index];
                $barcode   = $request->name[$index];

                $pitem = PurchaseItem::withCount('barcodes')->find($pitemId);

                if (!$pitem) {
                    DB::rollBack();
                    return response()->json(['message' => "Purchase item $pitemId tidak ditemukan"], 404);
                }

                // cek slot masih tersedia
                if ($pitem->barcodes_count >= $pitem->qty_kbn) {
                    DB::rollBack();
                    return response()->json(['message' => "Qty KBN Full untuk item {$pitem->id}!"], 403);
                }

                // cek duplikat
                $exist = Barcode::query()
                    ->where('barcode', $barcode)
                    ->where('product_id', $productId)
                    ->exists();

                if ($exist) {
                    DB::rollBack();
                    return response()->json(['message' => "Barcode $barcode sudah ada!"], 403);
                }
                // insert barcode
                Barcode::create([
                    'purchase_item_id'  => $pitemId,
                    'product_id'        => $productId,
                    'barcode'           => $barcode,
                    'available'         => 1,
                    'input_date'        => now(),
                ]);

                // update counter manual
                $pitem->barcodes_count++;

                // cek apakah purchase parent bisa di-close
                $purchase = $pitem->purchase;
                $allFilled = !$purchase->items()
                    ->whereRaw('(SELECT COUNT(*) FROM barcodes WHERE barcodes.purchase_item_id = purchase_items.id) < qty_kbn')
                    ->exists();
                if ($allFilled && $purchase->status !== 'close') {
                    $purchase->update(['status' => 'close']);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Semua barcode berhasil disimpan!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Error : ' . $th->getMessage()], 500);
        }
    }

    public function get(Request $request, Purchase $purchase)
    {
        try {
            if (!$request->filled('barcode')) {
                throw new Exception('Tidak Ditemukan!');
            }
            $barcode = $request->barcode;
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
