<?php

namespace Tests\Unit;

use App\Services\AhpService;
use Tests\TestCase;

class AhpServiceTest extends TestCase
{
    private AhpService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AhpService();
    }

    /** @test */
    public function it_returns_valid_true_for_perfectly_consistent_matrix(): void
    {
        // Matriks identik (semua 1) → CR = 0, selalu konsisten
        $matriks = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1],
        ];

        $result = $this->service->hitungBobot($matriks);

        $this->assertTrue($result['valid']);
        $this->assertLessThanOrEqual(0.10, $result['cr']);
    }

    /** @test */
    public function it_returns_equal_weights_for_identity_matrix(): void
    {
        $matriks = [
            [1, 1, 1],
            [1, 1, 1],
            [1, 1, 1],
        ];

        $result = $this->service->hitungBobot($matriks);

        // Bobot harus sama rata ≈ 1/3 masing-masing
        foreach ($result['bobot'] as $w) {
            $this->assertEqualsWithDelta(1 / 3, $w, 0.0001);
        }
    }

    /** @test */
    public function it_returns_valid_false_for_inconsistent_matrix(): void
    {
        // Matriks sangat tidak konsisten
        $matriks = [
            [1, 9, 1 / 9],
            [1 / 9, 1, 9],
            [9, 1 / 9, 1],
        ];

        $result = $this->service->hitungBobot($matriks);

        $this->assertFalse($result['valid']);
        $this->assertGreaterThan(0.10, $result['cr']);
    }

    /** @test */
    public function it_handles_single_criteria_matrix(): void
    {
        $matriks = [[1]];

        $result = $this->service->hitungBobot($matriks);

        $this->assertTrue($result['valid']);
        $this->assertEqualsWithDelta(1.0, $result['bobot'][0], 0.0001);
    }

    /** @test */
    public function it_returns_bobot_that_sum_to_one(): void
    {
        $matriks = [
            [1, 3, 5, 7, 9],
            [1 / 3, 1, 3, 5, 7],
            [1 / 5, 1 / 3, 1, 3, 5],
            [1 / 7, 1 / 5, 1 / 3, 1, 3],
            [1 / 9, 1 / 7, 1 / 5, 1 / 3, 1],
        ];

        $result = $this->service->hitungBobot($matriks);

        $sumBobot = array_sum($result['bobot']);
        $this->assertEqualsWithDelta(1.0, $sumBobot, 0.0001, 'Total bobot harus = 1');
    }

    /** @test */
    public function it_returns_correct_structure(): void
    {
        $matriks = [[1, 2], [0.5, 1]];

        $result = $this->service->hitungBobot($matriks);

        $this->assertArrayHasKey('valid', $result);
        $this->assertArrayHasKey('bobot', $result);
        $this->assertArrayHasKey('cr', $result);
        $this->assertArrayHasKey('ci', $result);
        $this->assertArrayHasKey('lambda_max', $result);
        $this->assertCount(2, $result['bobot']);
    }
}
