<?php

namespace Tests\Unit;

use App\Services\AhpService;
use App\Services\SawService;
use App\Models\Kriteria;
use App\Models\Warga;
use App\Models\Penilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SawServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_exception_when_no_kriteria(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data kriteria kosong.');

        (new SawService())->hitung('2026-05');
    }

    /** @test */
    public function it_throws_exception_when_bobot_not_set(): void
    {
        Kriteria::create([
            'kode' => 'C1',
            'nama' => 'Penghasilan',
            'tipe' => 'cost',
            'bobot' => null,
            'urutan' => 1,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('belum memiliki bobot');

        (new SawService())->hitung('2026-05');
    }

    /** @test */
    public function it_throws_exception_when_no_penilaian_data(): void
    {
        Kriteria::create([
            'kode' => 'C1',
            'nama' => 'Penghasilan',
            'tipe' => 'cost',
            'bobot' => 0.2,
            'urutan' => 1,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/Tidak ada data penilaian/');

        (new SawService())->hitung('2026-05');
    }

    /** @test */
    public function it_ranks_warga_correctly_with_single_criteria(): void
    {
        $kriteria = Kriteria::create([
            'kode' => 'C1',
            'nama' => 'Penghasilan',
            'tipe' => 'cost',
            'bobot' => 1.0,
            'urutan' => 1,
        ]);

        $warga1 = Warga::create(['nama' => 'A', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);
        $warga2 = Warga::create(['nama' => 'B', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);

        // Cost: nilai lebih kecil = skor lebih tinggi (lebih miskin = lebih prioritas)
        Penilaian::create(['warga_id' => $warga1->id, 'kriteria_id' => $kriteria->id, 'nilai_raw' => 'Kurang dari 1 juta', 'nilai_numerik' => 5, 'periode' => '2026-05']);
        Penilaian::create(['warga_id' => $warga2->id, 'kriteria_id' => $kriteria->id, 'nilai_raw' => 'Lebih dari 3 juta', 'nilai_numerik' => 1, 'periode' => '2026-05']);

        $hasil = (new SawService())->hitung('2026-05');

        // Ranking 1 = warga1 (nilai_numerik 1 -> min/val = 1/1 = 1.0 setelah normalisasi cost)
        // min = 1 (warga2), max = 5 (warga1), untuk cost: r = min/val
        // warga2: r = 1/1 = 1.0, warga1: r = 1/5 = 0.2
        // Jadi warga2 (penghasilan "Lebih dari 3 juta" = nilai 1) justru lebih kecil nilainya → skor cost lebih tinggi

        $this->assertCount(2, $hasil);
        $this->assertEquals(1, $hasil[0]['ranking']);
        $this->assertEquals(2, $hasil[1]['ranking']);
        $this->assertGreaterThan($hasil[1]['skor_akhir'], $hasil[0]['skor_akhir']);
    }

    /** @test */
    public function it_returns_correct_result_structure(): void
    {
        $kriteria = Kriteria::create([
            'kode' => 'C1',
            'nama' => 'Penghasilan',
            'tipe' => 'cost',
            'bobot' => 1.0,
            'urutan' => 1,
        ]);
        $warga = Warga::create(['nama' => 'Test', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);
        Penilaian::create(['warga_id' => $warga->id, 'kriteria_id' => $kriteria->id, 'nilai_raw' => 'test', 'nilai_numerik' => 3, 'periode' => '2026-05']);

        $hasil = (new SawService())->hitung('2026-05');

        $this->assertArrayHasKey('warga_id', $hasil[0]);
        $this->assertArrayHasKey('skor_akhir', $hasil[0]);
        $this->assertArrayHasKey('ranking', $hasil[0]);
        $this->assertArrayHasKey('status', $hasil[0]);
    }

    /** @test */
    public function it_assigns_correct_status_thresholds(): void
    {
        $k = Kriteria::create(['kode' => 'C1', 'nama' => 'Test', 'tipe' => 'benefit', 'bobot' => 1.0, 'urutan' => 1]);
        $w1 = Warga::create(['nama' => 'W1', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);
        $w2 = Warga::create(['nama' => 'W2', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);
        $w3 = Warga::create(['nama' => 'W3', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X']);

        // benefit: max = 6 (w1), mid = 3 (w2), low = 1 (w3)
        Penilaian::create(['warga_id' => $w1->id, 'kriteria_id' => $k->id, 'nilai_raw' => 'a', 'nilai_numerik' => 6, 'periode' => '2026-05']);
        Penilaian::create(['warga_id' => $w2->id, 'kriteria_id' => $k->id, 'nilai_raw' => 'b', 'nilai_numerik' => 3, 'periode' => '2026-05']);
        Penilaian::create(['warga_id' => $w3->id, 'kriteria_id' => $k->id, 'nilai_raw' => 'c', 'nilai_numerik' => 1, 'periode' => '2026-05']);

        $hasil = (new SawService())->hitung('2026-05');

        $byWarga = collect($hasil)->keyBy('warga_id');
        // w1: 6/6 = 1.0 → prioritas (>=0.75)
        $this->assertEquals('prioritas', $byWarga[$w1->id]['status']);
        // w2: 3/6 = 0.5 → layak (>=0.50)
        $this->assertEquals('layak', $byWarga[$w2->id]['status']);
        // w3: 1/6 ≈ 0.167 → tidak_layak
        $this->assertEquals('tidak_layak', $byWarga[$w3->id]['status']);
    }
}
