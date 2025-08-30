<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class OutboundController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Outbound::query()->with(['karyawan'])->withCount(['items']);
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        $karyawans = Karyawan::all();
        return view('outbound.index', compact('karyawans'));
    }

    public function show(Request $request, Outbound $outbound)
    {
        $data = $outbound->load(['items.product', 'karyawan']);
        if ($request->ajax()) {
            return response()->json(['data' => $data]);
        }
        $products = Product::all();
        return view('outbound.show', compact('data', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'karyawan_id'   => 'required|exists:karyawans,id',
            // 'number'        => 'required|unique:outbounds,number',
            'date'          => 'required|date_format:Y-m-d H:i:s',
            'desc'          => 'nullable|max:200',
        ]);
        $karyawan_id = $request->karyawan_id;
        $karyawan = Karyawan::find($karyawan_id);
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->date);
        $last = Outbound::query()
            ->where('karyawan_id', $karyawan_id)
            ->whereMonth('date', $date->month)
            ->count();
        $order = str_pad(($last ?? 0) + 1, 2, '0', STR_PAD_LEFT);
        $monthYear = $date->format('mY');
        $number = $karyawan->id_card . '-' . $monthYear . '-' . $order;
        Outbound::create([
            'karyawan_id'   => $request->karyawan_id,
            'number'        => $number,
            'date'          => $request->date,
            'desc'          => $request->desc,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Outbound $outbound)
    {
        $this->validate($request, [
            'karyawan_id' => 'required|exists:karyawans,id',
            // 'number'    => 'required|unique:outbounds,number,' . $outbound->id,
            'date'      => 'required|date_format:Y-m-d H:i:s',
            'desc'      => 'nullable|max:200',
        ]);
        $outbound->update([
            'karyawan_id' => $request->karyawan_id,
            // 'number'    => $request->number,
            'date'      => $request->date,
            'desc'      => $request->desc,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Outbound $outbound)
    {
        $outbound->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
