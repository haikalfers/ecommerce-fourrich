<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterTokoController extends Controller
{
    /**
     * Tampilkan form register toko siswa kelas 11
     */
    public function showForm(): View
    {
        $coAdmins = User::where('role', 'co_admin')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $kelasList = Kelas::active()
            ->kelas11()
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'jurusan']);

        return view('auth.register-toko', compact('coAdmins', 'kelasList'));
    }

    /**
     * Proses registrasi toko siswa
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp'       => ['required', 'string', 'max:20'],
            'kelas_id'    => ['required', 'exists:kelas,id'],
            'co_admin_id' => ['required', 'exists:users,id'],
            'nama_toko'   => ['required', 'string', 'max:100', 'unique:toko,nama_toko'],
            'deskripsi_toko' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'email.unique'        => 'Email sudah terdaftar.',
            'nama_toko.unique'    => 'Nama toko sudah digunakan, pilih nama lain.',
            'kelas_id.required'   => 'Pilih kelas kamu.',
            'co_admin_id.required'=> 'Pilih guru pembimbing.',
        ]);

        // Validasi tambahan: co_admin_id harus role co_admin
        $coAdmin = User::where('id', $request->co_admin_id)
            ->where('role', 'co_admin')
            ->first();

        if (! $coAdmin) {
            return back()->withErrors(['co_admin_id' => 'Guru pembimbing tidak valid.'])->withInput();
        }

        // Validasi kelas harus kelas XI
        $kelas = Kelas::find($request->kelas_id);
        if (! str_starts_with($kelas->nama_kelas, 'XI')) {
            return back()->withErrors(['kelas_id' => 'Hanya siswa kelas XI yang bisa mendaftar toko.'])->withInput();
        }

        // Buat user
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'toko',
            'kelas_id'    => $request->kelas_id,
            'co_admin_id' => $request->co_admin_id,
            'no_hp'       => $request->no_hp,
            'is_active'   => true,
        ]);

        // Buat toko (status: pending, menunggu approve admin)
        Toko::create([
            'user_id'      => $user->id,
            'co_admin_id'  => $request->co_admin_id,
            'nama_toko'    => $request->nama_toko,
            'deskripsi'    => $request->deskripsi_toko,
            'status'       => 'pending',
            'wallet_saldo' => 0,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('toko.dashboard')
            ->with('success', 'Akun berhasil dibuat! Toko kamu sedang menunggu persetujuan admin.');
    }
}
