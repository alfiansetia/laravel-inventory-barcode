<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\OutboundItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdjustmentController extends Controller
{
    function index()
    {
        return view('adjustment.index');
    }

    function save(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        $data = Excel::toArray([], $request->file('file'));
        $rows = $data[0];

        DB::beginTransaction();
        try {
            $inbounds = [];
            $outbounds = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // skip header

                $kode = trim($row[0]);
                $stokReal = (int) $row[1];

                $product = Product::where('code', $kode)->first();

                if (!$product) {
                    throw new \Exception("Produk dengan kode {$kode} tidak ditemukan di sistem!");
                }

                $stokSistem = $product->stock;

                if ($stokReal > $stokSistem) {
                    $inbounds[] = [
                        'product_id' => $product->id,
                        'qty'        => $stokReal - $stokSistem,
                    ];
                } elseif ($stokReal < $stokSistem) {
                    $outbounds[] = [
                        'product_id' => $product->id,
                        'qty'        => $stokSistem - $stokReal,
                    ];
                }
            }

            // === proses inbound ===
            if (count($inbounds) > 0) {
                $purchase = Purchase::create([
                    'supplier_id'   => null,
                    'purchase_date' => now(),
                    'notes'         => 'Adjustment stok inbound ' . now()->format('d-m-Y'),
                ]);

                foreach ($inbounds as $item) {
                    $purchaseItem = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id'  => $item['product_id'],
                        'qty'         => $item['qty'],
                        'price'       => 0,
                    ]);

                    PurchaseTransaction::create([
                        'purchase_item_id' => $purchaseItem->id,
                        'qty'              => $item['qty'],
                        'type'             => 'IN',
                    ]);
                }
            }

            // === proses outbound ===
            if (count($outbounds) > 0) {
                $outbound = Outbound::create([
                    'date'  => now(),
                    'notes' => 'Adjustment stok outbound ' . now()->format('d-m-Y'),
                ]);

                foreach ($outbounds as $item) {
                    OutboundItem::create([
                        'outbound_id' => $outbound->id,
                        'product_id'  => $item['product_id'],
                        'qty'         => $item['qty'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Adjustment stok berhasil diproses',
                'inbounds_count'  => count($inbounds),
                'outbounds_count' => count($outbounds)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json(['message' => 'Success Import!']);
    }
}
