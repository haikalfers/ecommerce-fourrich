<?php

namespace App\Http\Controllers\CoAdmin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AwardController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $tokoIds = Toko::where('co_admin_id', auth()->id())->pluck('id');

        $awards = Award::with(['toko.user.kelas'])
            ->whereIn('toko_id', $tokoIds)
            ->when($bulan, fn($q) => $q->where('bulan', $bulan))
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('co_admin.awards.index', compact('awards', 'bulan', 'tahun'));
    }
}
