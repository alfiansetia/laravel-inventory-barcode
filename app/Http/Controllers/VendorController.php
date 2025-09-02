<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Vendor::query();
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        return view('vendor.index');
    }

    public function show(Vendor $vendor)
    {
        return response()->json(['data' => $vendor]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'vendor_id' => 'required|string|max:100|unique:vendors,vendor_id',
            'npwp'      => 'required|max:100',
            'type'      => 'required|max:100',
        ]);
        Vendor::create([
            'name'      => $request->name,
            'vendor_id' => $request->vendor_id,
            'npwp'      => $request->npwp,
            'type'      => $request->type,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'vendor_id' => 'required|max:100|unique:vendors,vendor_id,' . $vendor->id,
            'npwp'      => 'required|max:100',
            'type'      => 'required|max:100',
        ]);
        $param = [
            'name'      => $request->name,
            'vendor_id' => $request->vendor_id,
            'npwp'      => $request->npwp,
            'type'      => $request->type,
        ];
        $vendor->update($param);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
