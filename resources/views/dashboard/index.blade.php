@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan sistem dan status alur proses SPK')

@section('content')

{{-- Stepper --}}
<div class="bg-white rounded-2xl border border-gray-100 p-5 mb-6 shadow-sm">
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Alur Proses SPK</p>
    <div class="flex items-center gap-2 flex-wrap">
        @foreach(['1. Input AHP', '2. Import Data', '3. Hitung SAW', '4. Lihat Hasil'] as $i => $step)
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold border
                {{ $currentStep > $i  ? 'bg-green-100 text-green-700 border-green-200'
                : ($currentStep == $i ? 'bg-blue-100 text-blue-700 border-blue-200'
                                      : 'bg-gray-100 text-gray-400 border-gray-200') }}">
                @if($currentStep > $i)<i class="fas fa-check mr-1"></i>@endif
                {{ $step }}
            </span>
            @if($i < 3)<div class="flex-1 h-0.5 bg-gray-100 min-w-4 hidden sm:block"></div>@endif
        @endforeach
    </div>
</div>

{{-- Metric Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    {{-- Total Warga --}}
    <div class="metric-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $totalWarga }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Total Warga Terdaftar</p>
    </div>

    {{-- Warga Dinilai --}}
    <div class="metric-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-clipboard-check text-indigo-600"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $wargaDinilai }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Sudah Dinilai ({{ now()->format('M Y') }})</p>
    </div>

    {{-- Layak --}}
    <div class="metric-card bg-white rounded-2xl border border-green-100 p-5 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-circle-check text-green-600"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-700">{{ $jumlahLayak }}</p>
        <p class="text-xs text-gray-400 mt-1 font-medium">Warga Layak & Prioritas</p>
    </div>

    {{-- CR Ratio --}}
    <div class="metric-card bg-white rounded-2xl border {{ $bobotSudahAda ? 'border-green-100' : 'border-orange-100' }} p-5 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 {{ $bobotSudahAda ? 'bg-green-50' : 'bg-orange-50' }} rounded-xl flex items-center justify-center">
                <i class="fas fa-sliders {{ $bobotSudahAda ? 'text-green-600' : 'text-orange-500' }}"></i>
            </div>
        </div>
        <p class="text-3xl font-bold {{ $bobotSudahAda ? 'text-green-700' : 'text-orange-600' }}">
            {{ $bobotSudahAda ? ($crRatio ?? '✓') : '—' }}
        </p>
        <p class="text-xs text-gray-400 mt-1 font-medium">CR Ratio AHP {{ $bobotSudahAda ? '(Konsisten)' : '(Belum ada)' }}</p>
    </div>
</div>

{{-- Content Bottom --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Top 5 Ranking --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">🏆 Top 5 Ranking Periode {{ now()->format('M Y') }}</h3>
            <a href="{{ route('hasil.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Warga</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">RT/RW</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Skor V</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($top5 as $h)
                    <tr class="table-row-hover">
                        <td class="px-6 py-3.5">
                            @if($h->ranking == 1)
                                <span class="w-7 h-7 inline-flex items-center justify-center bg-amber-400 text-white text-xs font-bold rounded-full">1</span>
                            @elseif($h->ranking == 2)
                                <span class="w-7 h-7 inline-flex items-center justify-center bg-gray-300 text-gray-700 text-xs font-bold rounded-full">2</span>
                            @elseif($h->ranking == 3)
                                <span class="w-7 h-7 inline-flex items-center justify-center bg-amber-600 text-white text-xs font-bold rounded-full">3</span>
                            @else
                                <span class="text-gray-400 font-semibold ml-2">{{ $h->ranking }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $h->warga->nama }}</td>
                        <td class="px-6 py-3.5 text-gray-500 text-xs">RT {{ $h->warga->rt }} / RW {{ $h->warga->rw }}</td>
                        <td class="px-6 py-3.5 text-right font-bold text-blue-700">{{ number_format($h->skor_akhir, 4) }}</td>
                        <td class="px-6 py-3.5 text-center">
                            @if($h->status === 'prioritas')
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-lg">Prioritas</span>
                            @elseif($h->status === 'layak')
                                <span class="bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">Layak</span>
                            @else
                                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-lg">Tidak Layak</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                            <i class="fas fa-calculator mb-3 text-3xl text-gray-200 block"></i>
                            Belum ada hasil kalkulasi SAW.<br>
                            <a href="{{ route('saw.index') }}" class="text-blue-500 hover:underline text-xs mt-1 inline-block">Hitung SAW sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Panduan Cepat --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">📋 Panduan Penggunaan</h3>
        <div class="space-y-3">
            @php
                $steps = [
                    ['icon'=>'fa-sliders','color'=>'blue','title'=>'1. Input Matriks AHP','desc'=>'Masukkan perbandingan antar kriteria. CR harus ≤ 0.10'],
                    ['icon'=>'fa-user-plus','color'=>'indigo','title'=>'2. Tambah Data Warga','desc'=>'Input data warga beserta nilai untuk setiap kriteria'],
                    ['icon'=>'fa-calculator','color'=>'purple','title'=>'3. Hitung SAW','desc'=>'Jalankan kalkulasi normalisasi dan pembobotan otomatis'],
                    ['icon'=>'fa-trophy','color'=>'amber','title'=>'4. Lihat Hasil','desc'=>'Tampilkan ranking dan rekomendasikan penerima bansos'],
                ];
            @endphp
            @foreach($steps as $s)
            <div class="flex gap-3 p-3 rounded-xl bg-gray-50 hover:bg-{{ $s['color'] }}-50 transition-colors">
                <div class="w-8 h-8 bg-{{ $s['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $s['icon'] }} text-{{ $s['color'] }}-600 text-xs"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-700">{{ $s['title'] }}</p>
                    <p class="text-[11px] text-gray-400 mt-0.5 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
