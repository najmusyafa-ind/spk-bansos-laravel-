<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warga;
use App\Models\Penilaian;
use App\Models\Kriteria;

class WargaDummySeeder extends Seeder
{
    // Mapping nilai untuk seeder dummy
    private array $penghasilanMap = [
        'Kurang dari 1 juta' => 5,
        '1-2 juta'           => 4,
        '2-3 juta'           => 2,
        'Lebih dari 3 juta'  => 1,
    ];

    private array $tanggunganMap = [
        '1 orang'    => 1,
        '2-3 orang'  => 2,
        '4-5 orang'  => 4,
        '>= 6 orang' => 6,
    ];

    private array $rumahMap = [
        'Sangat buruk' => 5,
        'Buruk'        => 4,
        'Sedang'       => 3,
        'Baik'         => 1,
    ];

    private array $pekerjaanMap = [
        'Tidak bekerja' => 5,
        'Tidak tetap'   => 3,
        'Tetap/PNS'     => 1,
    ];

    private array $asetMap = [
        'Tidak ada' => 5,
        'Sedikit'   => 3,
        'Banyak'    => 1,
    ];

    public function run(): void
    {
        $periode = now()->format('Y-m');
        $kriterias = Kriteria::orderBy('urutan')->get()->keyBy('kode');

        $wargaData = [
            ['nama' => 'Budi Santoso',     'rt' => '001', 'rw' => '002', 'kelurahan' => 'Sukamaju',
             'penghasilan' => 'Kurang dari 1 juta', 'tanggungan' => '>= 6 orang', 'rumah' => 'Sangat buruk', 'pekerjaan' => 'Tidak bekerja', 'aset' => 'Tidak ada'],
            ['nama' => 'Siti Aminah',      'rt' => '001', 'rw' => '002', 'kelurahan' => 'Sukamaju',
             'penghasilan' => 'Kurang dari 1 juta', 'tanggungan' => '4-5 orang',   'rumah' => 'Buruk',       'pekerjaan' => 'Tidak bekerja', 'aset' => 'Tidak ada'],
            ['nama' => 'Ahmad Fauzi',      'rt' => '002', 'rw' => '002', 'kelurahan' => 'Sukamaju',
             'penghasilan' => '1-2 juta',           'tanggungan' => '4-5 orang',   'rumah' => 'Buruk',       'pekerjaan' => 'Tidak tetap',   'aset' => 'Sedikit'],
            ['nama' => 'Dewi Rahayu',      'rt' => '002', 'rw' => '002', 'kelurahan' => 'Sukamaju',
             'penghasilan' => '1-2 juta',           'tanggungan' => '2-3 orang',   'rumah' => 'Sedang',      'pekerjaan' => 'Tidak tetap',   'aset' => 'Sedikit'],
            ['nama' => 'Joko Purnomo',     'rt' => '003', 'rw' => '001', 'kelurahan' => 'Sukamaju',
             'penghasilan' => '2-3 juta',           'tanggungan' => '2-3 orang',   'rumah' => 'Sedang',      'pekerjaan' => 'Tidak tetap',   'aset' => 'Sedikit'],
            ['nama' => 'Rina Marlina',     'rt' => '003', 'rw' => '001', 'kelurahan' => 'Sukamaju',
             'penghasilan' => 'Kurang dari 1 juta', 'tanggungan' => '4-5 orang',   'rumah' => 'Sangat buruk', 'pekerjaan' => 'Tidak bekerja', 'aset' => 'Tidak ada'],
            ['nama' => 'Hendra Kusuma',    'rt' => '001', 'rw' => '003', 'kelurahan' => 'Sukamaju',
             'penghasilan' => '2-3 juta',           'tanggungan' => '1 orang',     'rumah' => 'Baik',        'pekerjaan' => 'Tetap/PNS',     'aset' => 'Banyak'],
            ['nama' => 'Sri Wahyuni',      'rt' => '002', 'rw' => '003', 'kelurahan' => 'Sukamaju',
             'penghasilan' => '1-2 juta',           'tanggungan' => '2-3 orang',   'rumah' => 'Buruk',       'pekerjaan' => 'Tidak bekerja', 'aset' => 'Tidak ada'],
            ['nama' => 'Bambang Sugiarto', 'rt' => '003', 'rw' => '003', 'kelurahan' => 'Sukamaju',
             'penghasilan' => 'Lebih dari 3 juta',  'tanggungan' => '1 orang',     'rumah' => 'Baik',        'pekerjaan' => 'Tetap/PNS',     'aset' => 'Banyak'],
            ['nama' => 'Fitri Handayani',  'rt' => '001', 'rw' => '001', 'kelurahan' => 'Sukamaju',
             'penghasilan' => 'Kurang dari 1 juta', 'tanggungan' => '>= 6 orang',  'rumah' => 'Sangat buruk', 'pekerjaan' => 'Tidak bekerja', 'aset' => 'Tidak ada'],
        ];

        foreach ($wargaData as $wd) {
            $warga = Warga::updateOrCreate(
                ['nama' => $wd['nama']],
                ['rt' => $wd['rt'], 'rw' => $wd['rw'],
                 'kelurahan' => $wd['kelurahan'], 'alamat' => 'Jl. Contoh No. ' . rand(1, 100) . ', ' . $wd['kelurahan']]
            );

            $penilaians = [
                'C1' => ['raw' => $wd['penghasilan'], 'map' => $this->penghasilanMap],
                'C2' => ['raw' => $wd['tanggungan'],  'map' => $this->tanggunganMap],
                'C3' => ['raw' => $wd['rumah'],       'map' => $this->rumahMap],
                'C4' => ['raw' => $wd['pekerjaan'],   'map' => $this->pekerjaanMap],
                'C5' => ['raw' => $wd['aset'],        'map' => $this->asetMap],
            ];

            foreach ($penilaians as $kode => $p) {
                if (!isset($kriterias[$kode])) continue;

                Penilaian::updateOrCreate(
                    ['warga_id' => $warga->id, 'kriteria_id' => $kriterias[$kode]->id, 'periode' => $periode],
                    ['nilai_raw' => $p['raw'], 'nilai_numerik' => $p['map'][$p['raw']] ?? 1]
                );
            }
        }
    }
}
