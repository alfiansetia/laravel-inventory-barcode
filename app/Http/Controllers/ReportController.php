<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    function index()
    {
        // 
    }

    function history(Request $request, Product $product)
    {
        $from = $request->from;
        $to = $request->to;
        $date_from = Carbon::createFromFormat('Y-m-d', $from);
        $date_to = Carbon::createFromFormat('Y-m-d', $to);
    }
}
