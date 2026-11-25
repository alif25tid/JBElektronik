<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('mahasiswa.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'no_whatsapp' => ['nullable', 'string', 'max:25'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'no_whatsapp' => $validated['no_whatsapp'],
        ]);

        return redirect()
            ->route('mahasiswa.profile.index')
            ->with('success', 'Data diri berhasil diperbarui.');
    }
}