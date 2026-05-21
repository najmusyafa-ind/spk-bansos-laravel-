<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warga;
use App\Models\Kriteria;
use App\Models\Hasil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function dashboard_shows_correct_statistics()
    {
        // Setup data
        Warga::create(['nama' => 'Budi', 'rt' => '001', 'rw' => '001', 'kelurahan' => 'X', 'status_aktif' => true]);
        Warga::create(['nama' => 'Andi', 'rt' => '002', 'rw' => '001', 'kelurahan' => 'X', 'status_aktif' => true]);
        // Inactive warga shouldn't count in dashboard totalWarga
        Warga::create(['nama' => 'Siti', 'rt' => '003', 'rw' => '001', 'kelurahan' => 'X', 'status_aktif' => false]);

        Kriteria::create(['kode' => 'C1', 'nama' => 'Penghasilan', 'tipe' => 'cost', 'bobot' => 0.5, 'urutan' => 1]);
        Kriteria::create(['kode' => 'C2', 'nama' => 'Tanggungan', 'tipe' => 'benefit', 'bobot' => 0.5, 'urutan' => 2]);

        $periodeAktif = now()->format('Y-m');

        Hasil::create(['warga_id' => 1, 'periode' => $periodeAktif, 'skor_akhir' => 0.8, 'ranking' => 1, 'status' => 'prioritas']);
        Hasil::create(['warga_id' => 2, 'periode' => $periodeAktif, 'skor_akhir' => 0.6, 'ranking' => 2, 'status' => 'layak']);

        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);

        // Assert view contains specific variable data passed via compact()
        $response->assertViewHas('totalWarga', 2);
        $response->assertViewHas('totalKriteria', 2);
        $response->assertViewHas('bobotSudahAda', true);
        $response->assertViewHas('jumlahLayak', 2); // 1 prioritas + 1 layak = 2
        $response->assertViewHas('jumlahPrioritas', 1); // 1 prioritas
    }

    /** @test */
    public function dashboard_shows_empty_state_correctly()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalWarga', 0);
        $response->assertViewHas('totalKriteria', 0);
        $response->assertViewHas('bobotSudahAda', false);
        $response->assertViewHas('hasils');
        $this->assertEmpty($response->original->gatherData()['hasils']);
    }
}
