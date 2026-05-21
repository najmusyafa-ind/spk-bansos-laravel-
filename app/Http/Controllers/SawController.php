<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Services\SawService;
use Illuminate\Http\Request;

class SawController extends Controller
{
    public function index()
    {
        $periodeAktif = now()->format('Y-m');
        $periode      = request('periode', $periodeAktif);

        // Daftar periode yang tersedia (ada data penilaian)
        $periodes = \App\Models\Penilaian::select('periode')
            ->distinct()->orderByDesc('periode')->pluck('periode');

        $hasils = Hasil::with('warga')->where('periode', $periode)
            ->orderBy('ranking')->get();

        return view('saw.index', compact('periodeAktif', 'periode', 'periodes', 'hasils'));
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'periode' => ['required', 'regex:/^\d{4}-\d{2}$/'],
        ]);

        try {
            $hasil  = (new SawService())->hitung($request->periode);
            $count  = count($hasil);
            return redirect()->route('saw.index', ['periode' => $request->periode])
                ->with('success', "Kalkulasi SAW selesai! {$count} warga berhasil diperingkat untuk periode {$request->periode}.");
        } catch (\Exception $e) {
            return back()->withErrors(['saw' => $e->getMessage()]);
        }
    }
}
