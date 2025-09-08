<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\OutboundItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdjustmentController extends Controller
{
    function index()
    {
        return view('adjustment.index');
    }

    public function save(Request $request)
    {
        $request->validate([
            'date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:' . Carbon::now()->startOfMonth()->format('Y-m-d'),
                'before_or_equal:' . Carbon::now()->endOfMonth()->format('Y-m-d'),
            ],
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);


        $data = Excel::toArray([], $request->file('file'));
        $rows = collect($data[0])->skip(1); // skip header row
        $dt = now()->format('ymdHis');
        $date = Carbon::createFromFormat('Y-m-d', $request->date);

        DB::beginTransaction();
        try {
            // ✅ cek duplikat kode produk
            $duplicates = $rows->pluck(0)->map(fn($c) => trim($c))->duplicates();
            if ($duplicates->isNotEmpty()) {
                throw new \Exception("Terdapat duplikat kode produk di file Excel: " . $duplicates->unique()->implode(', '));
            }

            $inbounds = collect();
            $outbounds = collect();

            $rows->each(function ($row) use ($inbounds, $outbounds) {
                $kode = trim($row[0]);
                $stokReal = (int) $row[1];

                $product = Product::query()
                    ->withSum('trx as in', 'qty')
                    ->withSum('out as out', 'qty')
                    ->where('code', $kode)->first();

                if (!$product) {
                    throw new \Exception("Produk dengan kode {$kode} tidak ditemukan di sistem!");
                }

                $stokSistem = $product->stock;

                if ($stokReal > $stokSistem) {
                    $inbounds->push([
                        'product_id' => $product->id,
                        'qty'        => $stokReal - $stokSistem,
                    ]);
                } elseif ($stokReal < $stokSistem) {
                    $outbounds->push([
                        'product_id' => $product->id,
                        'qty'        => $stokSistem - $stokReal,
                    ]);
                }
            });

            // === proses inbound ===
            if ($inbounds->isNotEmpty()) {
                $purchase = Purchase::create([
                    'po_no'     => "P-ADJ-" . $dt,
                    'vendor_id' => null,
                    'status'    => 'close',
                ]);

                $inbounds->each(function ($item) use ($purchase, $date) {
                    $purchaseItem = PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id'  => $item['product_id'],
                    ]);

                    PurchaseTransaction::create([
                        'purchase_item_id' => $purchaseItem->id,
                        'product_id'       => $item['product_id'],
                        'date'             => $date->format('Y-m-d H:i:s'),
                        'qty'              => $item['qty'],
                    ]);
                });
            }

            // === proses outbound ===
            if ($outbounds->isNotEmpty()) {
                $outbound = Outbound::create([
                    'date'   => $date->format('Y-m-d H:i:s'),
                    'number' => "O-ADJ-" . $dt,
                    'desc'   => 'Adjustment stok outbound',
                ]);

                $outbounds->each(function ($item) use ($outbound) {
                    OutboundItem::create([
                        'outbound_id' => $outbound->id,
                        'product_id'  => $item['product_id'],
                        'qty'         => $item['qty'],
                    ]);
                });
            }

            DB::commit();

            return response()->json([
                'message'         => 'Adjustment stok berhasil diproses',
                'inbounds_count'  => $inbounds->count(),
                'outbounds_count' => $outbounds->count()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
