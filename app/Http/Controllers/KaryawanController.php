<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
            'id_absen'  => 'required|string|max:100|unique:karyawans,id_absen,' . $karyawan->id,
            'id_card'   => 'required|string|max:100|unique:karyawans,id_card,' . $karyawan->id,
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
                $id_absen = $row[0];
                $id_card  = $row[1];
                $name     = $row[2];

                // cek apakah sudah ada
                $exists = Karyawan::where('id_absen', $id_absen)
                    ->orWhere('id_card', $id_card)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Data duplikat ditemukan di baris " . ($index + 2) .
                        " (id_absen: $id_absen / id_card: $id_card)");
                }

                Karyawan::create([
                    'id_absen' => $id_absen,
                    'id_card'  => $id_card,
                    'name'     => $name,
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
