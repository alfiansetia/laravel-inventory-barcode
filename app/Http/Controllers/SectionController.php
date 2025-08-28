<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Section::query();
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        return view('section.index');
    }

    public function show(Section $section)
    {
        return response()->json(['data' => $section]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'code'      => 'required|string|max:100|unique:sections,code',
        ]);
        Section::create([
            'name'      => $request->name,
            'code'      => $request->code,
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, Section $section)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'code'      => 'required|string|max:100|unique:sections,code,' . $section->id,
        ]);
        $section->update([
            'name'      => $request->name,
            'code'      => $request->code,
        ]);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
