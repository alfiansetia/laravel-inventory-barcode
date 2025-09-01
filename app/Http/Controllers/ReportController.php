<?php

namespace App\Http\Controllers;

use App\Models\OutboundItem;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    function index()
    {
        return view('report.index');
    }

    function data(Request $request)
    {
        $from = $request->from ?? date('Y-m-d');
        $to   = $request->to ?? date('Y-m-d');

        $date_from = Carbon::createFromFormat('Y-m-d', $from)->startOfDay();
        $date_to   = Carbon::createFromFormat('Y-m-d', $to)->endOfDay();

        // Total in sebelum periode
        $in_before = PurchaseTransaction::select('product_id', DB::raw('SUM(qty) as total_in'))
            ->where('date', '<', $date_from)
            ->groupBy('product_id')
            ->pluck('total_in', 'product_id');

        // Total out sebelum periode
        $out_before = OutboundItem::select('product_id', DB::raw('SUM(qty) as total_out'))
            ->whereHas('outbound', function ($q) use ($date_from) {
                $q->where('date', '<', $date_from);
            })
            ->groupBy('product_id')
            ->pluck('total_out', 'product_id');

        // Total in dalam periode
        $ins = PurchaseTransaction::select('product_id', DB::raw('SUM(qty) as total_in'))
            ->whereBetween('date', [$date_from, $date_to])
            ->groupBy('product_id')
            ->pluck('total_in', 'product_id');

        // Total out dalam periode
        $outs = OutboundItem::select('product_id', DB::raw('SUM(qty) as total_out'))
            ->whereHas('outbound', function ($q) use ($date_from, $date_to) {
                $q->whereBetween('date', [$date_from, $date_to]);
            })
            ->groupBy('product_id')
            ->pluck('total_out', 'product_id');

        // Ambil semua produk dan gabungkan
        $rekap = Product::select('id', 'code', 'name')->get()->map(function ($product) use ($in_before, $out_before, $ins, $outs) {
            $awal_in  = $in_before[$product->id] ?? 0;
            $awal_out = $out_before[$product->id] ?? 0;
            $stok_awal = $awal_in - $awal_out;

            $in  = $ins[$product->id] ?? 0;
            $out = $outs[$product->id] ?? 0;

            $ending = $stok_awal + $in - $out;

            return [
                'product_id' => $product->id,
                'code'       => $product->code,
                'name'       => $product->name,
                'stok_awal'  => $stok_awal,
                'in'         => $in,
                'out'        => $out,
                'ending'     => $ending,
            ];
        });


        return DataTables::of($rekap)->addIndexColumn()->toJson();
    }
}
