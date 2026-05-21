@extends('layouts.admin')
@section('title', 'Simulasi Bobot')
@section('page_title', 'Simulasi Bobot SAW')
@section('page_subtitle', 'Geser slider bobot tiap kriteria secara bebas dan lihat perubahan ranking secara real-time')

@section('styles')
<style>
    .slider-track { -webkit-appearance: none; appearance: none; height: 6px; border-radius: 4px; background: #e2e8f0; outline: none; }
    .slider-track::-webkit-slider-thumb { -webkit-appearance: none; width: 18px; height: 18px; border-radius: 50%; background: #1e2d7a; cursor: pointer; box-shadow: 0 2px 6px rgba(30,45,122,0.4); transition: transform 0.1s; }
    .slider-track::-webkit-slider-thumb:hover { transform: scale(1.2); }
    .rank-badge { transition: all 0.3s ease; }
    .row-animate { transition: all 0.4s ease; }
    #hasil-table tbody tr { transition: background 0.3s ease; }
</style>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

    {{-- Slider Panel --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Periode --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-3">Pilih Periode</h3>
            <select id="periode-select" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white cursor-pointer">
                @foreach($periodes as $p)
                <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>

        {{-- Sliders --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Bobot Kriteria</h3>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Total bobot:</p>
                    <p id="total-bobot" class="text-sm font-black text-gray-800">0.00%</p>
                </div>
            </div>

            <div id="bobot-warning" class="hidden mb-4 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl text-xs">
                <i class="fas fa-triangle-exclamation mr-1.5"></i>
                Total bobot tidak 100% — sistem akan normalisasi otomatis saat hitung.
            </div>

            <div class="space-y-5" id="sliders-container">
                @foreach($kriterias as $k)
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-[#1e2d7a] text-white text-[10px] font-bold rounded-md flex items-center justify-center">{{ $k->kode }}</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $k->nama }}</span>
                            <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $k->tipe === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $k->tipe }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <input type="number" id="val-{{ $k->id }}" min="0" max="100" step="1"
                                   value="{{ $k->bobot ? round($k->bobot * 100) : 20 }}"
                                   class="w-12 text-center border border-gray-200 rounded-lg text-xs font-bold py-1 bg-gray-50"
                                   oninput="syncSlider({{ $k->id }}, this.value)">
                            <span class="text-xs text-gray-400">%</span>
                        </div>
                    </div>
                    <input type="range" id="slider-{{ $k->id }}" min="0" max="100" step="1"
                           value="{{ $k->bobot ? round($k->bobot * 100) : 20 }}"
                           data-kriteria-id="{{ $k->id }}"
                           class="slider-track w-full"
                           oninput="syncInput({{ $k->id }}, this.value)">
                    <div class="flex justify-between text-[10px] text-gray-300 mt-0.5">
                        <span>0%</span>
                        <span class="text-gray-400 font-medium">AHP: {{ $k->bobot ? number_format($k->bobot * 100, 1) . '%' : '—' }}</span>
                        <span>100%</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 pt-5 border-t border-gray-100 space-y-2">
                <button onclick="hitungSimulasi()"
                        class="w-full py-3 bg-[#1e2d7a] hover:bg-[#172264] text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20 hover:-translate-y-0.5">
                    <i class="fas fa-play mr-2"></i> Hitung Simulasi
                </button>
                <button onclick="resetKeBobot()"
                        class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 font-semibold text-sm rounded-xl transition-all border border-gray-200">
                    <i class="fas fa-rotate-left mr-1.5"></i> Reset ke Bobot AHP Asli
                </button>
            </div>
        </div>

        {{-- Info --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 text-xs text-blue-700 leading-relaxed">
            <p class="font-bold mb-2"><i class="fas fa-info-circle mr-1.5"></i>Cara Penggunaan:</p>
            <ol class="list-decimal list-inside space-y-1.5 text-blue-600">
                <li>Geser slider atau ubah nilai persentase</li>
                <li>Total bobot tidak harus 100% — sistem normalisasi otomatis</li>
                <li>Klik <strong>"Hitung Simulasi"</strong> untuk melihat perubahan ranking</li>
                <li>Bandingkan dengan ranking asli (kolom kanan)</li>
            </ol>
        </div>
    </div>

    {{-- Hasil Panel --}}
    <div class="xl:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Hasil Ranking Simulasi</h3>
            <div id="status-badge" class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-100 text-gray-400">
                Belum dihitung
            </div>
        </div>

        <div id="loading" class="hidden flex-1 flex items-center justify-center p-12">
            <div class="text-center">
                <div class="w-10 h-10 border-4 border-[#1e2d7a] border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-sm text-gray-400">Menghitung simulasi...</p>
            </div>
        </div>

        <div id="hasil-container" class="flex-1 overflow-y-auto">
            <div id="empty-state" class="flex items-center justify-center p-12 text-center">
                <div>
                    <i class="fas fa-sliders text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 text-sm">Atur slider bobot di kiri lalu klik<br><strong>"Hitung Simulasi"</strong></p>
                </div>
            </div>

            <table id="hasil-table" class="w-full text-sm hidden">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-20">Rank</th>
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Skor V</th>
                        <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="hasil-body" class="divide-y divide-gray-50"></tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
const kriterias = @json($kriterias->map(fn($k) => ['id' => $k->id, 'bobot' => $k->bobot]));

function syncSlider(id, val) {
    document.getElementById(`slider-${id}`).value = val;
    updateTotalBobot();
}

function syncInput(id, val) {
    document.getElementById(`val-${id}`).value = val;
    updateTotalBobot();
}

function updateTotalBobot() {
    let total = 0;
    document.querySelectorAll('input[type=range]').forEach(el => {
        total += parseInt(el.value) || 0;
    });
    const display = document.getElementById('total-bobot');
    const warning = document.getElementById('bobot-warning');
    display.textContent = total + '%';
    display.className   = total === 100 ? 'text-sm font-black text-green-600' : 'text-sm font-black text-amber-600';
    total !== 100 ? warning.classList.remove('hidden') : warning.classList.add('hidden');
}

function resetKeBobot() {
    kriterias.forEach(k => {
        const val = k.bobot ? Math.round(k.bobot * 100) : 0;
        const slider = document.getElementById(`slider-${k.id}`);
        const input  = document.getElementById(`val-${k.id}`);
        if (slider) slider.value = val;
        if (input)  input.value  = val;
    });
    updateTotalBobot();
}

async function hitungSimulasi() {
    const periode = document.getElementById('periode-select').value;
    const bobot   = {};

    document.querySelectorAll('input[type=range]').forEach(el => {
        const kriteriaId = el.dataset.kriteriaId;
        bobot[kriteriaId] = parseFloat(el.value) / 100;
    });

    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('hasil-table').classList.add('hidden');
    document.getElementById('status-badge').textContent = 'Menghitung...';
    document.getElementById('status-badge').className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 animate-pulse';

    try {
        const resp = await fetch('{{ route("simulasi.hitung") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ bobot, periode }),
        });

        const data = await resp.json();

        if (!resp.ok) {
            alert(data.error || 'Terjadi kesalahan.');
            return;
        }

        renderHasil(data.hasil);
        document.getElementById('status-badge').textContent = `${data.hasil.length} warga diperingkat`;
        document.getElementById('status-badge').className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-green-100 text-green-700';

    } catch (e) {
        alert('Gagal menghitung simulasi. Cek koneksi server.');
        document.getElementById('status-badge').textContent = 'Error';
        document.getElementById('status-badge').className = 'text-xs font-semibold px-3 py-1.5 rounded-full bg-red-100 text-red-700';
    } finally {
        document.getElementById('loading').classList.add('hidden');
    }
}

function renderHasil(hasil) {
    const tbody = document.getElementById('hasil-body');
    tbody.innerHTML = '';

    hasil.forEach((row, i) => {
        const statusBadge = row.status === 'prioritas'
            ? '<span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg">Prioritas</span>'
            : row.status === 'layak'
            ? '<span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">Layak</span>'
            : '<span class="bg-gray-100 text-gray-400 text-xs font-bold px-2.5 py-1 rounded-lg">Tidak Layak</span>';

        const rankBadge = row.ranking <= 3
            ? `<span class="w-7 h-7 inline-flex items-center justify-center rounded-full text-xs font-bold ${row.ranking === 1 ? 'bg-amber-400 text-white' : row.ranking === 2 ? 'bg-gray-300 text-gray-700' : 'bg-amber-600 text-white'}">${row.ranking}</span>`
            : `<span class="text-gray-400 font-bold text-xs">${row.ranking}</span>`;

        const bgClass = row.status === 'prioritas' ? 'bg-red-50/40' : '';

        tbody.innerHTML += `
        <tr class="row-animate hover:bg-gray-50 ${bgClass}">
            <td class="px-5 py-3.5">${rankBadge}</td>
            <td class="px-5 py-3.5 font-semibold text-gray-800">${row.nama}</td>
            <td class="px-5 py-3.5 text-right font-black text-blue-700">${row.skor.toFixed(4)}</td>
            <td class="px-5 py-3.5 text-center">${statusBadge}</td>
        </tr>`;
    });

    document.getElementById('hasil-table').classList.remove('hidden');
}

// Init
updateTotalBobot();
</script>
@endsection
