<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\AhpComparison;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AhpController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('urutan')->get();

        // Ambil matriks dari DB jika sudah ada
        $matriksDB = AhpComparison::all()->keyBy(fn($c) => $c->kriteria_baris_id . '_' . $c->kriteria_kolom_id);
        $ids = $kriterias->pluck('id')->toArray();

        $matriks = [];
        foreach ($ids as $i => $idBaris) {
            foreach ($ids as $j => $idKolom) {
                if ($i === $j) {
                    $matriks[$i][$j] = 1;
                } else {
                    $key = $idBaris . '_' . $idKolom;
                    $matriks[$i][$j] = $matriksDB->has($key)
                        ? (float) $matriksDB[$key]->nilai
                        : 1;
                }
            }
        }

        $ahpResult = null;
        if ($matriksDB->isNotEmpty()) {
            $matriksArr = [];
            foreach ($ids as $i => $idBaris) {
                foreach ($ids as $j => $idKolom) {
                    $matriksArr[$i][$j] = $matriks[$i][$j];
                }
            }
            $ahpResult = (new AhpService())->hitungBobot($matriksArr);
        }

        return view('ahp.index', compact('kriterias', 'matriks', 'ahpResult'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'matriks' => 'required|array',
            'matriks.*' => 'array',
        ]);

        $matriksInput = $request->input('matriks');
        $kriterias = Kriteria::orderBy('urutan')->get();
        $n = $kriterias->count();

        // Validasi dimensi
        if (count($matriksInput) !== $n) {
            return back()->withErrors(['matriks' => 'Dimensi matriks tidak sesuai jumlah kriteria.']);
        }

        // Hitung AHP terlebih dahulu sebelum menyentuh DB
        $result = (new AhpService())->hitungBobot($matriksInput);

        if (!$result['valid']) {
            return back()->withErrors([
                'cr' => 'Consistency Ratio = ' . $result['cr'] .
                    '. Nilai melebihi 0.10. Harap revisi perbandingan agar lebih konsisten.',
            ])->withInput();
        }

        // Simpan dalam satu transaksi agar atomic: truncate + insert + update bobot
        $ids = $kriterias->pluck('id')->toArray();

        DB::transaction(function () use ($ids, $matriksInput, $result) {
            // Hapus data lama
            AhpComparison::truncate();

            // Insert matriks baru (hanya off-diagonal)
            foreach ($ids as $i => $idBaris) {
                foreach ($ids as $j => $idKolom) {
                    if ($i !== $j) {
                        AhpComparison::create([
                            'kriteria_baris_id' => $idBaris,
                            'kriteria_kolom_id' => $idKolom,
                            'nilai' => $matriksInput[$i][$j] ?? 1,
                        ]);
                    }
                }
            }

            // Update bobot pada setiap kriteria
            foreach ($result['bobot'] as $i => $w) {
                Kriteria::where('id', $ids[$i])->update(['bobot' => $w]);
            }
        });

        session(['last_cr' => $result['cr']]);

        return redirect()->route('ahp.index')
            ->with('success', 'Bobot AHP berhasil disimpan! CR = ' . $result['cr'] . ' ✓ Konsisten');
    }
}
