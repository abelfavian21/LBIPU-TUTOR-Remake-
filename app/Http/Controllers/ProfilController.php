<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('profil.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'no_telp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'email' => ['nullable', 'email', 'max:150'],
        ], [
            'no_telp.regex' => 'No telepon hanya boleh berisi angka, spasi, + dan -.',
        ]);

        auth()->user()->update($data);

        return redirect()->route('profil.edit')->with('success', 'Data diri berhasil diperbarui.');
    }
}
