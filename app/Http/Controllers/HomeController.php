<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Outbound;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $purchase_count = Purchase::where('status', 'open')->count();

        $outbound_count = Outbound::whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->count();

        $karyawan_count = Karyawan::count();
        $product_count  = Product::count();

        return view('home', compact(
            'purchase_count',
            'outbound_count',
            'karyawan_count',
            'product_count',
        ));
    }
}
