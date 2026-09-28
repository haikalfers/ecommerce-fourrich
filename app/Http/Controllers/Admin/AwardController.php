<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Toko;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AwardController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $awards = Award::with(['toko.user.kelas', 'toko.coAdmin'])
            ->when($bulan, fn($q) => $q->where('bulan', $bulan))
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tokos = Toko::aktif()->with('user')->orderBy('nama_toko')->get();

        return view('admin.awards.index', compact('awards', 'tokos', 'bulan', 'tahun'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'toko_id'          => ['required', 'exists:toko,id'],
            'jenis_award'      => ['required', 'in:toko_terlaris,best_newcomer,most_reviewed,most_consistent,top_per_kelas'],
            'bulan'            => ['required', 'integer', 'between:1,12'],
            'tahun'            => ['required', 'integer', 'min:2020', 'max:2099'],
            'nilai_pencapaian' => ['nullable', 'numeric', 'min:0'],
            'keterangan'       => ['nullable', 'string', 'max:255'],
        ]);

        Award::updateOrCreate(
            [
                'toko_id'     => $validated['toko_id'],
                'jenis_award' => $validated['jenis_award'],
                'bulan'       => $validated['bulan'],
                'tahun'       => $validated['tahun'],
            ],
            [
                'nilai_pencapaian' => $validated['nilai_pencapaian'] ?? 0,
                'keterangan'       => $validated['keterangan'] ?? null,
            ]
        );

        return back()->with('success', 'Penghargaan (Award) berhasil diberikan kepada toko.');
    }

    public function destroy(Award $award): RedirectResponse
    {
        $award->delete();

        return back()->with('success', 'Penghargaan berhasil dihapus.');
    }
}
