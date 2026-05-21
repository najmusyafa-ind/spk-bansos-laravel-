<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ranking Bansos {{ $periode }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a2e; line-height: 1.5; }
        
        .header { background: #1e2d7a; color: white; padding: 24px 32px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .header p { font-size: 10px; color: #93c5fd; }
        .header .meta { margin-top: 12px; display: flex; gap: 24px; }
        .header .meta-item span { display: block; font-size: 9px; color: #93c5fd; text-transform: uppercase; letter-spacing: 0.5px; }
        .header .meta-item strong { font-size: 13px; }

        .stats { display: flex; gap: 12px; padding: 0 32px; margin-bottom: 20px; }
        .stat-card { flex: 1; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; text-align: center; }
        .stat-card .val { font-size: 22px; font-weight: 800; }
        .stat-card .lbl { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .stat-prioritas .val { color: #dc2626; }
        .stat-layak .val     { color: #16a34a; }
        .stat-tidak .val     { color: #9ca3af; }

        .section-title { font-size: 11px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 32px 10px; padding-bottom: 6px; border-bottom: 2px solid #1e2d7a; }

        table { width: calc(100% - 64px); margin: 0 32px; border-collapse: collapse; font-size: 10px; }
        thead tr { background: #1e2d7a; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-weight: 600; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; }
        thead th:last-child { text-align: center; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody tr.prioritas { background: #fef2f2; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; }
        tbody td.center { text-align: center; }
        tbody td.right { text-align: right; }

        .rank-cell { font-weight: 800; font-size: 12px; }
        .rank-1 { color: #d97706; }
        .rank-2 { color: #6b7280; }
        .rank-3 { color: #92400e; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 8px; font-weight: 700; text-transform: uppercase; }
        .badge-prioritas { background: #fee2e2; color: #991b1b; }
        .badge-layak     { background: #dcfce7; color: #166534; }
        .badge-tidak     { background: #f3f4f6; color: #6b7280; }

        .footer { margin: 24px 32px 16px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .footer p { font-size: 9px; color: #9ca3af; }

        .kriteria-info { padding: 0 32px; margin: 20px 0; }
        .kriteria-grid { display: flex; gap: 8px; flex-wrap: wrap; }
        .kriteria-pill { border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 10px; font-size: 9px; }
        .kriteria-pill strong { display: block; font-size: 10px; margin-bottom: 2px; }
        .benefit-pill { border-color: #86efac; background: #f0fdf4; }
        .cost-pill    { border-color: #fca5a5; background: #fef2f2; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>Laporan Ranking Penerima Bantuan Sosial</h1>
        <p>Sistem Pendukung Keputusan — Metode AHP + SAW</p>
        <div class="meta">
            <div class="meta-item">
                <span>Periode</span>
                <strong>{{ $periode }}</strong>
            </div>
            <div class="meta-item">
                <span>Total Peserta</span>
                <strong>{{ $hasils->count() }} Warga</strong>
            </div>
            <div class="meta-item">
                <span>Tanggal Cetak</span>
                <strong>{{ now()->isoFormat('DD MMMM YYYY') }}</strong>
            </div>
            <div class="meta-item">
                <span>Dicetak pukul</span>
                <strong>{{ now()->format('H:i') }} WIB</strong>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats">
        <div class="stat-card stat-prioritas">
            <div class="val">{{ $stats['prioritas'] }}</div>
            <div class="lbl">★ Prioritas Utama</div>
        </div>
        <div class="stat-card stat-layak">
            <div class="val">{{ $stats['layak'] }}</div>
            <div class="lbl">✓ Layak Terima</div>
        </div>
        <div class="stat-card stat-tidak">
            <div class="val">{{ $stats['tidak_layak'] }}</div>
            <div class="lbl">✗ Tidak Layak</div>
        </div>
    </div>

    {{-- Kriteria Info --}}
    <div class="kriteria-info">
        <p class="section-title">Kriteria & Bobot AHP</p>
        <div class="kriteria-grid">
            @foreach($kriterias as $k)
            <div class="kriteria-pill {{ $k->tipe === 'benefit' ? 'benefit-pill' : 'cost-pill' }}">
                <strong>{{ $k->kode }} — {{ $k->nama }}</strong>
                Tipe: {{ strtoupper($k->tipe) }} | Bobot: {{ $k->bobot ? number_format($k->bobot, 4) : 'Belum ada' }}
            </div>
            @endforeach
        </div>
    </div>

    {{-- Tabel Ranking --}}
    <p class="section-title" style="margin-top: 20px;">Tabel Ranking Warga</p>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">Rank</th>
                <th>Nama Warga</th>
                <th>RT/RW</th>
                <th style="text-align: right;">Skor V (SAW)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasils as $h)
            <tr class="{{ $h->status === 'prioritas' ? 'prioritas' : '' }}">
                <td class="center rank-cell rank-{{ min($h->ranking, 4) === $h->ranking ? $h->ranking : 'default' }}">
                    {{ $h->ranking }}
                </td>
                <td><strong>{{ $h->warga->nama }}</strong></td>
                <td class="center" style="color: #6b7280;">RT {{ $h->warga->rt }} / {{ $h->warga->rw }}</td>
                <td class="right" style="font-weight: 700; color: #1e40af;">{{ number_format($h->skor_akhir, 4) }}</td>
                <td class="center">
                    @if($h->status === 'prioritas')
                        <span class="badge badge-prioritas">★ Prioritas</span>
                    @elseif($h->status === 'layak')
                        <span class="badge badge-layak">✓ Layak</span>
                    @else
                        <span class="badge badge-tidak">✗ Tidak Layak</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh Sistem SPK Bansos | AHP + SAW Method</p>
        <p>Periode: {{ $periode }} | Halaman 1</p>
    </div>

</body>
</html>
