<?php

namespace App\Http\Controllers;

use App\Models\Warga;
use App\Models\Kriteria;
use App\Models\Hasil;
use App\Models\Penilaian;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarga     = Warga::where('status_aktif', true)->count();
        $totalKriteria  = Kriteria::count();
        $periodeAktif   = now()->format('Y-m');

        // Status bobot AHP
        $bobotSudahAda  = Kriteria::whereNotNull('bobot')->count() === $totalKriteria && $totalKriteria > 0;
        $crRatio        = session('last_cr', null);

        // Data hasil periode aktif
        $hasils = Hasil::with('warga')
            ->where('periode', $periodeAktif)
            ->orderBy('ranking')
            ->get();

        $jumlahLayak     = $hasils->whereIn('status', ['layak', 'prioritas'])->count();
        $jumlahPrioritas = $hasils->where('status', 'prioritas')->count();
        $top5            = $hasils->take(5);

        // Data penilaian periode aktif
        $wargaDinilai = Penilaian::where('periode', $periodeAktif)
            ->distinct('warga_id')->count('warga_id');

        // Status alur proses
        $currentStep = 0;
        if ($bobotSudahAda)     $currentStep = 1; // AHP done
        if ($wargaDinilai > 0)  $currentStep = 2; // Data done
        if ($hasils->isNotEmpty()) $currentStep = 3; // SAW done

        return view('dashboard.index', compact(
            'totalWarga', 'totalKriteria', 'periodeAktif',
            'bobotSudahAda', 'crRatio', 'hasils', 'jumlahLayak',
            'jumlahPrioritas', 'top5', 'wargaDinilai', 'currentStep'
        ));
    }
}
