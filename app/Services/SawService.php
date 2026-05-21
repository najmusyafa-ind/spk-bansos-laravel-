<?php

namespace App\Services;

use App\Models\Hasil;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Warga;

class SawService
{
    public function hitung(string $periode, array $overrideBobot = [])
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        if ($kriterias->isEmpty()) {
            throw new \Exception('Data kriteria kosong.');
        }

        // Pakai bobot override (dari simulasi) atau bobot asli DB
        $bobot = [];
        $totalOverride = 0;

        foreach ($kriterias as $k) {
            if (!empty($overrideBobot)) {
                $val = $overrideBobot[$k->id] ?? 0;
                $totalOverride += $val;
                $bobot[$k->id] = $val;
            } else {
                $val = $k->bobot;
                if ($val === null) {
                    throw new \Exception("Kriteria {$k->kode} belum memiliki bobot. Silakan hitung matriks AHP terlebih dahulu.");
                }
                $bobot[$k->id] = $val;
            }
        }

        // Normalisasi override bobot jika totalnya tidak 1 (100%)
        if (!empty($overrideBobot) && $totalOverride > 0) {
            foreach ($bobot as $id => $val) {
                $bobot[$id] = $val / $totalOverride;
            }
        }

        // Ambil semua penilaian pada periode tersebut
        $wargas = Warga::whereHas('penilaians', function ($q) use ($periode) {
            $q->where('periode', $periode);
        })->with([
                    'penilaians' => function ($q) use ($periode) {
                        $q->where('periode', $periode);
                    }
                ])->get();

        if ($wargas->isEmpty()) {
            throw new \Exception("Tidak ada data penilaian warga untuk periode {$periode}.");
        }

        // Cari min max per kriteria
        $minMax = [];
        foreach ($kriterias as $k) {
            $nilaiKriteria = $wargas->pluck('penilaians')->flatten()->where('kriteria_id', $k->id)->pluck('nilai_numerik')->toArray();
            if (empty($nilaiKriteria))
                continue;

            $minMax[$k->id] = [
                'max' => max($nilaiKriteria),
                'min' => min($nilaiKriteria)
            ];
        }

        $hasilAkhir = [];
        foreach ($wargas as $warga) {
            // Cegah duplikasi jika query builder me-return duplikat
            if (isset($hasilAkhir[$warga->id]))
                continue;

            $skorV = 0;
            foreach ($warga->penilaians as $p) {
                $k = $kriterias->firstWhere('id', $p->kriteria_id);
                if (!$k || !isset($minMax[$k->id]))
                    continue;

                $b = $bobot[$k->id];
                $nilai = $p->nilai_numerik;

                // Normalisasi SAW
                if ($k->tipe === 'benefit') {
                    $r = $minMax[$k->id]['max'] == 0 ? 0 : $nilai / $minMax[$k->id]['max'];
                } else {
                    $r = $nilai == 0 ? 0 : $minMax[$k->id]['min'] / $nilai;
                }

                $skorV += ($b * $r);
            }

            $status = 'tidak_layak';
            if ($skorV >= 0.75)
                $status = 'prioritas';
            elseif ($skorV >= 0.50)
                $status = 'layak';

            $hasilAkhir[$warga->id] = [
                'warga_id' => $warga->id,
                'nama' => $warga->nama, // Untuk sorting/simulasi
                'periode' => $periode,
                'skor_akhir' => $skorV,
                'skor' => $skorV, // alias
                'status' => $status,
            ];
        }

        // Ubah kembali dari asosiatif array ke array berurutan
        $hasilAkhir = array_values($hasilAkhir);

        // Sorting by skor_akhir descending
        usort($hasilAkhir, function ($a, $b) {
            return $b['skor_akhir'] <=> $a['skor_akhir'];
        });

        // Tambah field ranking
        foreach ($hasilAkhir as $i => $h) {
            $hasilAkhir[$i]['ranking'] = $i + 1;
        }

        // Jika tidak ada override bobot, berarti ini hitung beneran, simpan ke DB
        if (empty($overrideBobot)) {
            // Insert atau Update agar tidak kena UNIQUE constraint violation
            foreach ($hasilAkhir as $h) {
                Hasil::updateOrCreate(
                    [
                        'warga_id' => $h['warga_id'],
                        'periode' => $h['periode'],
                    ],
                    [
                        'skor_akhir' => $h['skor_akhir'],
                        'ranking' => $h['ranking'],
                        'status' => $h['status'],
                    ]
                );
            }

            // Hapus hasil lama yang warganya sudah tidak ada di penilaian periode ini
            $wargaIds = array_column($hasilAkhir, 'warga_id');
            Hasil::where('periode', $periode)->whereNotIn('warga_id', $wargaIds)->delete();
        }

        return $hasilAkhir;
    }
}
