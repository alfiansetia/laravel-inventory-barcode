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
        $filters = $request->only(['purchase_item_id', 'product_id', 'available']);
        $query = Barcode::query()->with('purchase_item')->filter($filters);
        return DataTables::eloquent($query)->toJson();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'purchase_item_id'  => 'required|exists:purchase_items,id',
            'product_id'        => 'required|exists:products,id',
            'barcode'           => 'required|max:100',
        ]);
        $barcode = $request->barcode;
        $pitem = PurchaseItem::withCount('barcodes')->find($request->purchase_item_id);
        if ($pitem->barcodes_count >= $pitem->qty_kbn) {
            return response()->json(['message' => "Qty KBN Full!"], 403);
        }
        $exist = Barcode::query()
            ->where('barcode', $barcode)
            ->where('product_id', $request->product_id)
            ->first();
        if ($exist) {
            return response()->json(['message' => "Barcode Barang Sudah Ada!"], 403);
        }
        Barcode::create([
            'purchase_item_id'  => $request->purchase_item_id,
            'product_id'        => $request->product_id,
            'barcode'           => $barcode,
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
                throw new Exception('Tidak Ditemukan!');
            }
            $barcode = $request->barcode;
            $state = false;
            $exist = Barcode::where('barcode', $barcode)->first();
            if ($exist) {
                $state = true;
            }
            $pattern = '/^[A-Z0-9\-]+\+\d+(?:-\d+)?\+\d+$/i';

            if (!preg_match($pattern, $barcode)) {
                throw new Exception('Barcode Tidak Valid!');
            }
            try {
                list($productCode, $poRit, $qtyKbn) = array_map('trim', explode('+', $barcode));
                list($poNo, $rit) = array_pad(array_map('trim', explode('-', $poRit)), 2, null);
            } catch (\Throwable $th) {
                throw new Exception('Tidak Valid!');
            }

            if ($qtyKbn < 1) {
                throw new Exception('Qty Kbn Barcode Tidak Valid!');
            }

            $purchaseItem = PurchaseItem::query()->with(['purchase.vendor', 'product'])
                ->whereRelation('purchase', 'status', 'open')
                ->whereRelation('purchase', 'po_no', $poNo)
                // ->whereRelation('purchase', 'rit', $rit)
                ->whereRelation('product', 'code', $productCode)
                // ->whereHas('product', function ($q) use ($productCode) {
                //     $q->where('code', $productCode);
                // })
                ->where('qty_kbn', '>=', $qtyKbn)
                ->first();

            if (!$purchaseItem) {
                throw new Exception('Purchase item not found!');
            }

            return response()->json([
                'data'  => $purchaseItem,
                'state' => $state
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error Barcode ' . $barcode . ' : ' . $e->getMessage()], 400);
        }
    }
}
