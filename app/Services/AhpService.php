<?php

namespace App\Services;

class AhpService
{
    private array $ri = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49
    ];

    public function hitungBobot(array $matriks)
    {
        $n = count($matriks);
        if ($n < 1)
            return ['valid' => false, 'error' => 'Matriks kosong'];

        $jumlahKolom = array_fill(0, $n, 0);
        foreach ($matriks as $i => $baris) {
            foreach ($baris as $j => $nilai) {
                $jumlahKolom[$j] += $nilai;
            }
        }

        $matriksNormalisasi = [];
        $bobot = [];
        for ($i = 0; $i < $n; $i++) {
            $sumBobot = 0;
            for ($j = 0; $j < $n; $j++) {
                $val = $jumlahKolom[$j] == 0 ? 0 : $matriks[$i][$j] / $jumlahKolom[$j];
                $matriksNormalisasi[$i][$j] = $val;
                $sumBobot += $val;
            }
            $bobot[$i] = $sumBobot / $n;
        }

        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $lambdaMax += $bobot[$i] * $jumlahKolom[$i];
        }

        $ci = ($n - 1 > 0) ? ($lambdaMax - $n) / ($n - 1) : 0;
        $riValue = $this->ri[$n] ?? 1.49;
        $cr = $riValue == 0 ? 0 : $ci / $riValue;

        return [
            'valid' => $cr <= 0.10,
            'bobot' => $bobot,
            'lambda_max' => $lambdaMax,
            'ci' => $ci,
            'cr' => $cr
        ];
    }
}
