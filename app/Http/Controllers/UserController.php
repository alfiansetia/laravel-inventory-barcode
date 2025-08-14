<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();
            return DataTables::eloquent($query)->addIndexColumn()->toJson();
        }
        return view('user.index');
    }

    public function show(User $user)
    {
        return response()->json(['data' => $user]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'email'     => 'required|email|max:100|unique:users,email',
            'password'  => 'required|min:5',
        ]);
        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'Data Inserted!']);
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'      => 'required|max:100',
            'email'     => 'required|email|max:100|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:5',
        ]);
        $param = [
            'name'      => $request->name,
            'email'     => $request->email,
        ];
        if ($request->filled('password')) {
            $param['password'] = Hash::make($request->password);
        }
        $user->update($param);
        return response()->json(['message' => 'Data Updated!']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Data Deleted!']);
    }
}
