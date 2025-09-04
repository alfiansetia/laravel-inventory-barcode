<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $data = Excel::toCollection([], $file)[0]->skip(1);

            foreach ($data as $index => $row) {
                $id = $row[0];
                $name     = $row[1];
                $npwp  = $row[2];
                $type  = $row[3];

                // cek apakah sudah ada
                $exists = Vendor::where('vendor_id', $id)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Data duplikat ditemukan di baris " . ($index + 2) .
                        " (vendor_id: $id / name: $name)");
                }

                Vendor::create([
                    'vendor_id' => $id,
                    'name'      => $name,
                    'npwp'      => $npwp,
                    'type'      => $type,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'success Import']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal import: ' . $th->getMessage()], 500);
        }
    }
}
