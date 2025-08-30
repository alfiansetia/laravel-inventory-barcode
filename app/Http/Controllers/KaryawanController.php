<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Karyawan::query();
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        return view('karyawan.index');
    }

    public function show(Karyawan $karyawan)
    {
        return response()->json(['data' => $karyawan]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'id_absen'  => 'required|string|max:100|unique:karyawans,id_absen',
            'id_card'   => 'required|string|max:100|unique:karyawans,id_card',
        ]);
        Karyawan::create([
            'name'      => $request->name,
            'id_absen'  => $request->id_absen,
            'id_card'   => $request->id_card,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'absen'     => 'required|string|max:100|unique:sections,absen,' . $karyawan->id,
            'card'      => 'required|string|max:100|unique:sections,card,' . $karyawan->id,
        ]);
        $karyawan->update([
            'name'      => $request->name,
            'id_absen'  => $request->id_absen,
            'id_card'   => $request->id_card,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
