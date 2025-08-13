<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
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
}
