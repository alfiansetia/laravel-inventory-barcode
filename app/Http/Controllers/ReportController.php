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

        // Ambil total in per product
        $ins = PurchaseTransaction::select('product_id', DB::raw('SUM(qty) as total_in'))
            ->whereBetween('date', [$date_from, $date_to])
            ->groupBy('product_id')
            ->pluck('total_in', 'product_id');

        // Ambil total out per product
        $outs = OutboundItem::select('product_id', DB::raw('SUM(qty) as total_out'))
            ->whereHas('outbound', function ($q) use ($date_from, $date_to) {
                $q->whereBetween('date', [$date_from, $date_to]);
            })
            ->groupBy('product_id')
            ->pluck('total_out', 'product_id');

        // Ambil semua produk, lalu merge dengan hasil in/out
        $rekap = Product::select('id', 'code', 'name')->get()->map(function ($product) use ($ins, $outs) {
            $in  = $ins[$product->id] ?? 0;
            $out = $outs[$product->id] ?? 0;
            return [
                'product_id' => $product->id,
                'code'       => $product->code,
                'name'       => $product->name,
                'in'         => $in,
                'out'        => $out,
                'ending'     => $in - $out,
            ];
        });

        return DataTables::of($rekap)->addIndexColumn()->toJson();
    }
}
