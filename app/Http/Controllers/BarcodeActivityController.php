<?php

namespace App\Http\Controllers;

use App\Models\BarcodeActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BarcodeActivityController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['barcode_id']);
        $query = BarcodeActivity::query()->filter($filters);
        return DataTables::eloquent($query)->addIndexColumn()->toJson();
    }
}
