<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Services\SawService;
use Illuminate\Http\Request;

class SimulasiController extends Controller
{
    public function index(Request $request)
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        $periodeAktif = now()->format('Y-m');
        $periode = $request->input('periode', $periodeAktif);

        // Periodes yang punya data
        $periodes = Penilaian::select('periode')->distinct()->orderByDesc('periode')->pluck('periode');

        return view('simulasi.index', compact('kriterias', 'periode', 'periodeAktif', 'periodes'));
    }

    /**
     * AJAX endpoint — hitung ranking simulasi dengan bobot kustom.
     * Menggunakan SawService::hitung() dengan $overrideBobot agar logika terpusat.
     */
    public function hitung(Request $request)
    {
        $request->validate([
            'bobot' => 'required|array',
            'bobot.*' => 'required|numeric|min:0|max:1',
            'periode' => ['required', 'regex:/^\d{4}-\d{2}$/'],
        ]);

        $bobotInput = $request->input('bobot'); // [kriteria_id => bobot_kustom]
        $totalBobot = array_sum($bobotInput);

        if ($totalBobot <= 0) {
            return response()->json(['error' => 'Total bobot tidak boleh 0.'], 422);
        }

        try {
            // SawService sudah mendukung overrideBobot — delegate ke sana
            $hasil = (new SawService())->hitung($request->periode, $bobotInput);

            // Normalisasi bobot untuk ditampilkan di response
            $kriterias = Kriteria::orderBy('urutan')->get();
            $bobotNormal = [];
            foreach ($kriterias as $k) {
                $raw = (float) ($bobotInput[$k->id] ?? 0);
                $bobotNormal[$k->id] = $totalBobot > 0 ? $raw / $totalBobot : 0;
            }

            return response()->json([
                'hasil' => $hasil,
                'bobot_aktif' => $bobotNormal,
                'total_bobot' => $totalBobot,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
