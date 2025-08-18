<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'password_lama'       => 'required',
            'password_baru'       => 'required|min:6',
            'konfirmasi_password' => 'required|same:password_baru',
        ], [
            'password_lama.required'       => 'Password lama wajib diisi.',
            'password_baru.required'       => 'Password baru wajib diisi.',
            'password_baru.min'            => 'Password baru minimal 6 karakter.',
            'konfirmasi_password.required' => 'Konfirmasi password wajib diisi.',
            'konfirmasi_password.same'     => 'Konfirmasi password tidak sama dengan password baru.',
        ]);

        $user = Auth::user();

        // ✅ Cek password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
        }

        // ✅ Update password
        $user->password = Hash::make($request->password_baru);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui!');
    }
}
