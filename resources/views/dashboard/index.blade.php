@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan sistem dan status alur proses SPK')

@section('styles')
    <style>
        /* === STEPPER === */
        .stepper-wrap {
            background: #fff;
            border-radius: 18px;
            border: 1px solid #f1f5f9;
            padding: 20px 22px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .stepper-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #cbd5e1;
            margin-bottom: 14px;
        }

        .stepper-items {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stepper-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            white-space: nowrap;
        }

        .stepper-badge:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .stepper-badge.done {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }

        .stepper-badge.active {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            border-color: transparent;
        }

        .stepper-line {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            border-radius: 2px;
            min-width: 16px;
        }

        /* === METRIC CARDS GRID === */
        .metrics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 20px;
        }

        @media (min-width: 1024px) {
            .metrics-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 18px;
            }
        }

        .metric-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            padding: 18px 16px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -8px rgba(37, 99, 235, 0.14);
        }

        .metric-blob {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            filter: blur(18px);
            opacity: 0.6;
            pointer-events: none;
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
            transition: transform 0.25s;
        }

        .metric-card:hover .metric-icon {
            transform: scale(1.12);
        }

        .metric-number {
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
            position: relative;
            z-index: 1;
            line-height: 1;
        }

        .metric-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #94a3b8;
            margin-top: 6px;
            position: relative;
            z-index: 1;
        }

        /* === BOTTOM GRID === */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        @media (min-width: 1024px) {
            .bottom-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        /* === RANKING TABLE CARD === */
        .card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafbfc;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }

        .card-title-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .card-link {
            font-size: 12px;
            font-weight: 700;
            color: #3b82f6;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .card-link:hover {
            background: #eff6ff;
        }

        /* Ranking Table */
        .ranking-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ranking-table thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }

        .ranking-table th {
            padding: 12px 16px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            white-space: nowrap;
        }

        .ranking-table td {
            padding: 11px 16px;
            font-size: 13px;
        }

        .ranking-table tbody tr {
            border-bottom: 1px solid #f8fafc;
            transition: background 0.15s;
        }

        .ranking-table tbody tr:hover {
            background: #f0f7ff;
        }

        .ranking-table tbody tr:last-child {
            border-bottom: none;
        }

        .rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 900;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #fff;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .rank-2 {
            background: linear-gradient(135deg, #cbd5e1, #94a3b8);
            color: #fff;
        }

        .rank-3 {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: #fff;
        }

        .rank-n {
            background: #f1f5f9;
            color: #64748b;
        }

        .rt-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 7px;
        }

        .status-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 7px;
            white-space: nowrap;
        }

        .status-prioritas {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .status-layak {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .status-tidak {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .skor-text {
            font-weight: 800;
            font-size: 13px;
            color: #3b82f6;
        }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }

        .empty-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
            color: #cbd5e1;
        }

        /* === GUIDE CARD === */
        .guide-step {
            display: flex;
            gap: 14px;
            padding: 12px;
            border-radius: 14px;
            border: 1px solid transparent;
            cursor: default;
            transition: all 0.25s;
            margin-bottom: 8px;
        }

        .guide-step:hover {
            background: #fff;
            border-color: #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .guide-step:hover .guide-step-icon {
            transform: scale(1.1);
        }

        .guide-step-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            transition: transform 0.2s;
        }

        .guide-step-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .guide-step-desc {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.5;
        }
    </style>
@endsection

@section('content')

    {{-- Stepper --}}
    <div class="stepper-wrap">
        <div class="stepper-label">Alur Proses SPK</div>
        <div class="stepper-items">
            @php
                $steps = [
                    ['route' => 'ahp.index', 'label' => '1. Input AHP'],
                    ['route' => 'import.index', 'label' => '2. Import Data'],
                    ['route' => 'saw.index', 'label' => '3. Hitung SAW'],
                    ['route' => 'hasil.index', 'label' => '4. Lihat Hasil'],
                ];
            @endphp
            @foreach($steps as $i => $step)
                <a href="{{ route($step['route']) }}" style="text-decoration: none;"
                    class="stepper-badge {{ $currentStep > $i ? 'done' : ($currentStep == $i ? 'active' : '') }}">
                    @if($currentStep > $i)<i class="fas fa-check" style="margin-right:5px; color:#22c55e;"></i>@endif
                    {{ $step['label'] }}
                </a>
                @if($i < 3)
                <div class="stepper-line"></div>@endif
            @endforeach
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="metrics-grid">

        {{-- Total Warga --}}
        <div class="metric-card">
            <div class="metric-blob" style="background:#3b82f6;"></div>
            <div class="metric-icon" style="background:#eff6ff; color:#2563eb;">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-number">{{ $totalWarga }}</div>
            <div class="metric-label">Total Warga</div>
        </div>

        {{-- Warga Dinilai --}}
        <div class="metric-card">
            <div class="metric-blob" style="background:#6366f1;"></div>
            <div class="metric-icon" style="background:#eef2ff; color:#4f46e5;">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="metric-number">{{ $wargaDinilai }}</div>
            <div class="metric-label">Sudah Dinilai</div>
        </div>

        {{-- Layak --}}
        <div class="metric-card">
            <div class="metric-blob" style="background:#22c55e;"></div>
            <div class="metric-icon" style="background:#f0fdf4; color:#16a34a;">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="metric-number" style="color:#16a34a;">{{ $jumlahLayak }}</div>
            <div class="metric-label">Warga Layak</div>
        </div>

        {{-- CR Ratio --}}
        <div class="metric-card">
            <div class="metric-blob" style="background: {{ $bobotSudahAda ? '#22c55e' : '#f59e0b' }};"></div>
            <div class="metric-icon"
                style="background: {{ $bobotSudahAda ? '#f0fdf4' : '#fffbeb' }}; color: {{ $bobotSudahAda ? '#16a34a' : '#d97706' }};">
                <i class="fas fa-sliders"></i>
            </div>
            <div class="metric-number" style="color: {{ $bobotSudahAda ? '#16a34a' : '#d97706' }};">
                {{ $bobotSudahAda ? ($crRatio ?? '✓') : '—' }}
            </div>
            <div class="metric-label">CR Ratio AHP</div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="bottom-grid">

        {{-- TOP 5 Ranking --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-icon" style="background:#eef2ff; color:#4f46e5;">
                        <i class="fas fa-trophy"></i>
                    </div>
                    Top 5 Ranking SAW
                </div>
                <a href="{{ route('hasil.index') }}" class="card-link">Semua &rarr;</a>
            </div>
            <div style="overflow-x:auto;">
                <table class="ranking-table">
                    <thead>
                        <tr>
                            <th style="text-align:center; width:50px;">#</th>
                            <th>Nama Warga</th>
                            <th style="display:none;" class="show-md">Wilayah</th>
                            <th style="text-align:right;">Skor (V)</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top5 as $h)
                            <tr>
                                <td style="text-align:center;">
                                    @if($h->ranking == 1) <span class="rank-badge rank-1">1</span>
                                    @elseif($h->ranking == 2) <span class="rank-badge rank-2">2</span>
                                    @elseif($h->ranking == 3) <span class="rank-badge rank-3">3</span>
                                    @else <span class="rank-badge rank-n">{{ $h->ranking }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a;">{{ $h->warga->nama }}</div>
                                    <div class="rt-chip" style="margin-top:3px; display:inline-flex;">
                                        <i class="fas fa-map-marker-alt" style="font-size:10px;"></i>
                                        RT {{ str_pad($h->warga->rt, 3, '0', STR_PAD_LEFT) }} / RW
                                        {{ str_pad($h->warga->rw, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>
                                <td style="text-align:right;" class="skor-text">{{ number_format($h->skor_akhir, 4) }}</td>
                                <td style="text-align:center;">
                                    @if($h->status === 'prioritas')
                                        <span class="status-badge status-prioritas">Prioritas</span>
                                    @elseif($h->status === 'layak')
                                        <span class="status-badge status-layak">Layak</span>
                                    @else
                                        <span class="status-badge status-tidak">Tidak Layak</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon"><i class="fas fa-calculator"></i></div>
                                        <p style="font-size:13px; font-weight:600; color:#64748b; margin:0 0 6px;">Belum ada
                                            hasil kalkulasi SAW</p>
                                        <a href="{{ route('saw.index') }}"
                                            style="font-size:12px; color:#3b82f6; font-weight:700; text-decoration:none;">Hitung
                                            SAW sekarang &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PANDUAN --}}
        <div class="card" style="padding: 20px;">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:18px;">
                <div class="card-title-icon" style="background:#f0fdfa; color:#0d9488;">
                    <i class="fas fa-book-open" style="font-size:13px;"></i>
                </div>
                <span style="font-size:14px; font-weight:800; color:#0f172a;">Panduan Penggunaan</span>
            </div>

            @php
                $guides = [
                    ['icon' => 'fa-sliders', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'title' => '1. Atur Matriks AHP', 'desc' => 'Input bobot perbandingan antar kriteria, CR harus ≤ 0.10'],
                    ['icon' => 'fa-users', 'color' => '#6366f1', 'bg' => '#eef2ff', 'title' => '2. Tambah Data Warga', 'desc' => 'Input data beserta nilai setiap kriteria untuk masing-masing warga'],
                    ['icon' => 'fa-calculator', 'color' => '#9333ea', 'bg' => '#faf5ff', 'title' => '3. Jalankan SAW', 'desc' => 'Hitung normalisasi dan perangkingan otomatis seluruh warga'],
                    ['icon' => 'fa-file-lines', 'color' => '#16a34a', 'bg' => '#f0fdf4', 'title' => '4. Lihat & Ekspor Hasil', 'desc' => 'Evaluasi ranking penerima bansos & unduh laporan PDF'],
                ];
            @endphp

            @foreach($guides as $g)
                <div class="guide-step">
                    <div class="guide-step-icon" style="background:{{ $g['bg'] }}; color:{{ $g['color'] }};">
                        <i class="fas {{ $g['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="guide-step-title">{{ $g['title'] }}</div>
                        <div class="guide-step-desc">{{ $g['desc'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection