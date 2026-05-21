<?php

namespace App\Http\Controllers;

use App\Helpers\NilaiMapper;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        $periodeAktif = now()->format('Y-m');
        return view('import.index', compact('periodeAktif'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'periode' => ['required', 'regex:/^\d{4}-\d{2}$/'],
        ], [
            'file.required' => 'Pilih file CSV terlebih dahulu.',
            'file.mimes' => 'File harus berformat CSV (.csv).',
            'file.max' => 'Ukuran file maksimum 5 MB.',
            'periode.regex' => 'Format periode harus YYYY-MM.',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");

        // Auto-detect delimiter
        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 1000, $delimiter);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['file' => 'Format CSV tidak valid atau kosong.']);
        }

        // Bersihkan spasi/BOM pada header
        $header = array_map(function ($h) {
            return strtolower(trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $h)));
        }, $header);

        $kriterias = Kriteria::orderBy('urutan')->get()->keyBy('kode');
        $errors = [];
        $successCount = 0;
        $skipCount = 0;
        $rowNum = 1;

        DB::beginTransaction();
        try {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $rowNum++;

                // Map header ke data
                $row = [];
                foreach ($header as $i => $h) {
                    $row[$h] = $data[$i] ?? '';
                }

                try {
                    $nama = trim($row['nama'] ?? '');
                    $rt = trim($row['rt'] ?? '');
                    $rw = trim($row['rw'] ?? '');

                    if (empty($nama)) {
                        $errors[] = "Baris {$rowNum}: Nama kosong — dilewati.";
                        $skipCount++;
                        continue;
                    }

                    // Upsert warga
                    $warga = Warga::updateOrCreate(
                        ['nama' => $nama],
                        [
                            'rt' => $rt ?: '000',
                            'rw' => $rw ?: '000',
                            'kelurahan' => trim($row['kelurahan'] ?? 'Tidak diketahui'),
                            'alamat' => trim($row['alamat'] ?? ''),
                        ]
                    );

                    // Penilaian — gunakan NilaiMapper
                    $penilaianData = [
                        'C1' => $row['penghasilan'] ?? '',
                        'C2' => $row['tanggungan'] ?? '',
                        'C3' => $row['kondisi_rumah'] ?? $row['rumah'] ?? '',
                        'C4' => $row['pekerjaan'] ?? '',
                        'C5' => $row['aset'] ?? '',
                    ];

                    foreach ($penilaianData as $kode => $rawValue) {
                        if (!isset($kriterias[$kode]))
                            continue;

                        $nilaiNum = NilaiMapper::map($kode, (string) $rawValue);

                        if ($nilaiNum === null) {
                            $errors[] = "Baris {$rowNum} [{$nama}]: Nilai '{$rawValue}' untuk {$kode} tidak dikenal — diisi 1.";
                            $nilaiNum = 1;
                        }

                        Penilaian::updateOrCreate(
                            [
                                'warga_id' => $warga->id,
                                'kriteria_id' => $kriterias[$kode]->id,
                                'periode' => $request->periode,
                            ],
                            [
                                'nilai_raw' => (string) $rawValue,
                                'nilai_numerik' => $nilaiNum,
                            ]
                        );
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNum}: Error pada data spesifik — " . $e->getMessage();
                    $skipCount++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Fatal Error saat import. Perubahan dibatalkan: ' . $e->getMessage()]);
        } finally {
            fclose($handle);
        }

        $summary = "Import selesai! {$successCount} warga berhasil diimpor ke periode {$request->periode}.";
        if ($skipCount > 0) {
            $summary .= " {$skipCount} baris dilewati.";
        }

        return redirect()->route('import.index')
            ->with('success', $summary)
            ->with('import_errors', $errors)
            ->with('import_periode', $request->periode);
    }
}
