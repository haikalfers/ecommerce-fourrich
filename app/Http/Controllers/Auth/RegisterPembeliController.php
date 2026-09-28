<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterPembeliController extends Controller
{
    /**
     * Tampilkan form register pembeli
     */
    public function showForm(): View
    {
        $kelasList = Kelas::active()
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'jurusan']);

        return view('auth.register-pembeli', compact('kelasList'));
    }

    /**
     * Proses registrasi pembeli
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp'        => ['nullable', 'string', 'max:20'],
            'tipe_pembeli' => ['required', 'in:warga_sekolah,publik'],
            'kelas_id'     => ['required_if:tipe_pembeli,warga_sekolah', 'nullable', 'exists:kelas,id'],
        ], [
            'email.unique'          => 'Email sudah terdaftar.',
            'tipe_pembeli.required' => 'Pilih tipe pembeli.',
            'kelas_id.required_if'  => 'Pilih kelas kamu (warga sekolah wajib memilih kelas).',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'pembeli',
            'tipe_pembeli' => $request->tipe_pembeli,
            'kelas_id'     => $request->tipe_pembeli === 'warga_sekolah' ? $request->kelas_id : null,
            'no_hp'        => $request->no_hp,
            'is_active'    => true,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('katalog.index')
            ->with('success', 'Selamat datang, ' . $user->name . '! Akun berhasil dibuat.');
    }
}
