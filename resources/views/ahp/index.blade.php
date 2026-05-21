@extends('layouts.admin')
@section('title', 'Matriks AHP')
@section('page_title', 'Matriks AHP')
@section('page_subtitle', 'Input perbandingan berpasangan antar kriteria — CR harus ≤ 0.10')

@section('styles')
<style>
    .matrix-input { width: 72px; text-align: center; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 4px; font-size: 13px; font-weight: 600; transition: all 0.15s; }
    .matrix-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
    .matrix-diag { background: #f1f5f9; color: #64748b; border-color: #e2e8f0; }
    .cr-valid { color: #16a34a; }
    .cr-invalid { color: #dc2626; }
</style>
@endsection

@section('content')

@if($errors->has('cr'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-start gap-3">
    <i class="fas fa-triangle-exclamation text-red-500 mt-0.5"></i>
    <div class="text-sm font-medium">{{ $errors->first('cr') }}</div>
</div>
@endif

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-start gap-3">
    <i class="fas fa-circle-check text-green-500 mt-0.5"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Matriks AHP --}}
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Matriks Perbandingan Berpasangan ({{ $kriterias->count() }}x{{ $kriterias->count() }})</h3>
            @if($ahpResult)
                <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $ahpResult['valid'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    CR = {{ $ahpResult['cr'] }} {{ $ahpResult['valid'] ? '✓ Konsisten' : '✗ Tidak Konsisten' }}
                </span>
            @endif
        </div>

        <form method="POST" action="{{ route('ahp.simpan') }}" id="ahp-form">
            @csrf
            <div class="p-6 overflow-x-auto">
                <table class="mx-auto border-separate" style="border-spacing: 4px;">
                    <thead>
                        <tr>
                            <th class="p-2 bg-gray-50 rounded-lg text-gray-400 text-xs w-24"></th>
                            @foreach($kriterias as $k)
                            <th class="p-2 bg-[#1e2d7a] text-white text-xs font-bold rounded-lg text-center w-20">
                                {{ $k->kode }}<br>
                                <span class="font-normal text-blue-200 text-[10px]">{{ Str::limit($k->nama, 8) }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $i => $ki)
                        <tr>
                            <td class="p-2 bg-[#1e2d7a] text-white text-xs font-bold rounded-lg text-center">
                                {{ $ki->kode }}<br>
                                <span class="font-normal text-blue-200 text-[10px]">{{ Str::limit($ki->nama, 8) }}</span>
                            </td>
                            @foreach($kriterias as $j => $kj)
                            <td class="text-center">
                                @if($i === $j)
                                    <input type="hidden" name="matriks[{{$i}}][{{$j}}]" value="1">
                                    <div class="matrix-input matrix-diag cursor-default">1</div>
                                @else
                                    <input type="number" step="0.001" min="0.111" max="9"
                                           name="matriks[{{$i}}][{{$j}}]"
                                           id="m_{{$i}}_{{$j}}"
                                           value="{{ old("matriks.{$i}.{$j}", $matriks[$i][$j] ?? 1) }}"
                                           class="matrix-input"
                                           oninput="updateReciprocal({{$i}}, {{$j}}, this.value)">
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- CR Live Display --}}
            <div class="mx-6 mb-6 p-4 bg-gray-50 rounded-xl flex items-center gap-6 flex-wrap">
                <div>
                    <p class="text-xs text-gray-400 font-medium">Consistency Ratio (CR)</p>
                    <p id="cr-display" class="text-2xl font-bold text-gray-300 mt-0.5">Belum dihitung</p>
                </div>
                <div id="cr-status" class="hidden">
                    <span id="cr-badge" class="px-3 py-1.5 rounded-full text-sm font-bold"></span>
                </div>
                <div class="ml-auto flex gap-3">
                    <button type="button" onclick="hitungCR()"
                            class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold text-sm rounded-xl transition-all">
                        <i class="fas fa-sync mr-1.5"></i> Hitung CR
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/30">
                        <i class="fas fa-save mr-1.5"></i> Simpan Bobot
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Panel --}}
    <div class="space-y-5">

        {{-- Bobot Kriteria saat ini --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Bobot Kriteria (Hasil AHP)</h3>
            <div class="space-y-3">
                @foreach($kriterias as $k)
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-700">{{ $k->kode }} - {{ $k->nama }}</span>
                        <span class="text-blue-700">{{ $k->bobot ? number_format($k->bobot, 4) : '—' }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-[#1e2d7a] h-1.5 rounded-full transition-all" style="width: {{ $k->bobot ? ($k->bobot * 100) . '%' : '0%' }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Skala Saaty --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-3">Skala Perbandingan Saaty</h3>
            <div class="space-y-1.5">
                @foreach([1=>'Sama penting', 3=>'Sedikit lebih penting', 5=>'Cukup lebih penting', 7=>'Sangat lebih penting', 9=>'Mutlak lebih penting'] as $val => $def)
                <div class="flex gap-2 text-xs">
                    <span class="w-6 h-6 bg-[#1e2d7a] text-white rounded-lg flex items-center justify-center font-bold flex-shrink-0">{{ $val }}</span>
                    <span class="text-gray-600 self-center">{{ $def }}</span>
                </div>
                @endforeach
                <p class="text-[10px] text-gray-400 mt-2 pt-2 border-t border-gray-100">Nilai kebalikan (1/n) diisi otomatis.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const n = {{ $kriterias->count() }};

function updateReciprocal(i, j, val) {
    const reciprocal = document.getElementById(`m_${j}_${i}`);
    if (reciprocal && val > 0) {
        reciprocal.value = (1 / parseFloat(val)).toFixed(4);
    }
}

function hitungCR() {
    // Ambil matriks dari form
    const matriks = [];
    for (let i = 0; i < n; i++) {
        matriks[i] = [];
        for (let j = 0; j < n; j++) {
            const input = document.querySelector(`input[name="matriks[${i}][${j}]"]`);
            matriks[i][j] = parseFloat(input?.value || 1);
        }
    }

    // Hitung kolom sum
    const colSums = new Array(n).fill(0);
    for (let i = 0; i < n; i++) for (let j = 0; j < n; j++) colSums[j] += matriks[i][j];

    // Normalisasi & bobot
    const bobot = [];
    for (let i = 0; i < n; i++) {
        let rowSum = 0;
        for (let j = 0; j < n; j++) rowSum += matriks[i][j] / colSums[j];
        bobot[i] = rowSum / n;
    }

    // Lambda max
    let lambdaMax = 0;
    for (let i = 0; i < n; i++) {
        let rowSum = 0;
        for (let j = 0; j < n; j++) rowSum += matriks[i][j] * bobot[j];
        lambdaMax += rowSum / bobot[i];
    }
    lambdaMax /= n;

    const ci = (lambdaMax - n) / (n - 1);
    const ri = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
    const cr = n <= 2 ? 0 : ci / ri[n];

    const crDisplay = document.getElementById('cr-display');
    const crStatus  = document.getElementById('cr-status');
    const crBadge   = document.getElementById('cr-badge');

    crDisplay.textContent = cr.toFixed(4);
    crDisplay.className = cr <= 0.1 ? 'text-2xl font-bold mt-0.5 text-green-600' : 'text-2xl font-bold mt-0.5 text-red-600';

    crStatus.classList.remove('hidden');
    if (cr <= 0.1) {
        crBadge.textContent = '✓ Konsisten — Bobot dapat disimpan';
        crBadge.className = 'px-3 py-1.5 rounded-full text-sm font-bold bg-green-100 text-green-700';
    } else {
        crBadge.textContent = '✗ Tidak Konsisten — Harap revisi perbandingan';
        crBadge.className = 'px-3 py-1.5 rounded-full text-sm font-bold bg-red-100 text-red-700';
    }
}
</script>
@endsection
