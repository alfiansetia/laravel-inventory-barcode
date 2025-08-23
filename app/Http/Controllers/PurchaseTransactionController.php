<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseTransactionController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['purchase_item_id', 'product_id']);
        $query = PurchaseTransaction::query()->with('purchase_item')->filter($filters);
        return DataTables::eloquent($query)->addIndexColumn()->toJson();
    }
}
