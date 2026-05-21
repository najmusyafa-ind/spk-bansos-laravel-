<?php

namespace App\Helpers;

/**
 * Sumber tunggal (Single Source of Truth) untuk mapping nilai tekstual ke numerik.
 * Digunakan oleh WargaController (input manual) dan ImportController (import CSV).
 */
class NilaiMapper
{
    /**
     * Peta alias untuk setiap kriteria.
     * Key: string lowercase (nilai tekstual), Value: integer (nilai numerik SPK)
     */
    private static array $penghasilan = [
        'kurang dari 1 juta' => 5,
        '< 1 juta' => 5,
        '<1 juta' => 5,
        '1-2 juta' => 4,
        '1 - 2 juta' => 4,
        '2-3 juta' => 2,
        '2 - 3 juta' => 2,
        'lebih dari 3 juta' => 1,
        '> 3 juta' => 1,
        '>3 juta' => 1,
    ];

    private static array $tanggungan = [
        '1 orang' => 1,
        '1' => 1,
        '2-3 orang' => 2,
        '2 orang' => 2,
        '3 orang' => 2,
        '4-5 orang' => 4,
        '4 orang' => 4,
        '5 orang' => 4,
        '>= 6 orang' => 6,
        '6 orang' => 6,
        '> 6 orang' => 6,
        'lebih dari 5' => 6,
    ];

    private static array $rumah = [
        'sangat buruk' => 5,
        'buruk' => 4,
        'sedang' => 3,
        'cukup' => 3,
        'baik' => 1,
        'sangat baik' => 1,
    ];

    private static array $pekerjaan = [
        'tidak bekerja' => 5,
        'pengangguran' => 5,
        'tidak tetap' => 3,
        'buruh' => 3,
        'pedagang' => 3,
        'wiraswasta' => 3,
        'tetap' => 1,
        'tetap/pns' => 1,
        'pns' => 1,
        'asn' => 1,
        'karyawan tetap' => 1,
    ];

    private static array $aset = [
        'tidak ada' => 5,
        'tidak punya' => 5,
        'sedikit' => 3,
        'ada sedikit' => 3,
        'banyak' => 1,
        'cukup banyak' => 1,
    ];

    /**
     * Ambil nilai numerik dari teks. Case-insensitive & trim otomatis.
     * Mengembalikan null jika nilai tidak dikenal.
     */
    public static function map(string $kriteria, string $nilaiRaw): ?int
    {
        $key = strtolower(trim($nilaiRaw));

        return match ($kriteria) {
            'C1' => self::$penghasilan[$key] ?? null,
            'C2' => self::$tanggungan[$key] ?? null,
            'C3' => self::$rumah[$key] ?? null,
            'C4' => self::$pekerjaan[$key] ?? null,
            'C5' => self::$aset[$key] ?? null,
            default => null,
        };
    }

    /**
     * Ambil semua opsi yang tersedia untuk sebuah kriteria (untuk dropdown form).
     * Mengembalikan array unik nilai tampilan (tidak duplikat alias).
     */
    public static function options(string $kriteria): array
    {
        return match ($kriteria) {
            'C1' => ['Kurang dari 1 juta', '1-2 juta', '2-3 juta', 'Lebih dari 3 juta'],
            'C2' => ['1 orang', '2-3 orang', '4-5 orang', '>= 6 orang'],
            'C3' => ['Sangat buruk', 'Buruk', 'Sedang', 'Baik'],
            'C4' => ['Tidak bekerja', 'Tidak tetap', 'Tetap/PNS'],
            'C5' => ['Tidak ada', 'Sedikit', 'Banyak'],
            default => [],
        };
    }
}
