<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $kriterias = [
            ['kode' => 'C1', 'nama' => 'Penghasilan',    'tipe' => 'cost',    'bobot' => null, 'urutan' => 1,
             'deskripsi' => 'Penghasilan bulanan kepala keluarga. Semakin rendah, semakin diprioritaskan (Cost).'],
            ['kode' => 'C2', 'nama' => 'Tanggungan',     'tipe' => 'benefit', 'bobot' => null, 'urutan' => 2,
             'deskripsi' => 'Jumlah anggota keluarga yang menjadi tanggungan. Semakin banyak, semakin diprioritaskan (Benefit).'],
            ['kode' => 'C3', 'nama' => 'Kondisi Rumah',  'tipe' => 'benefit', 'bobot' => null, 'urutan' => 3,
             'deskripsi' => 'Kondisi fisik tempat tinggal. Semakin buruk kondisinya, semakin diprioritaskan (Benefit).'],
            ['kode' => 'C4', 'nama' => 'Pekerjaan',      'tipe' => 'benefit', 'bobot' => null, 'urutan' => 4,
             'deskripsi' => 'Status pekerjaan kepala keluarga. Tidak bekerja lebih diprioritaskan (Benefit).'],
            ['kode' => 'C5', 'nama' => 'Aset',           'tipe' => 'cost',    'bobot' => null, 'urutan' => 5,
             'deskripsi' => 'Kepemilikan aset berharga. Semakin sedikit aset, semakin diprioritaskan (Cost).'],
        ];

        foreach ($kriterias as $k) {
            Kriteria::updateOrCreate(['kode' => $k['kode']], $k);
        }
    }
}
