@extends('layouts.admin')
@section('title', 'Hasil Ranking')
@section('page_title', 'Hasil Ranking SPK')
@section('page_subtitle', 'Daftar peringkat penerima bantuan sosial berdasarkan metode AHP + SAW')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white border-l-4 border-l-red-500 border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-red-500 font-bold uppercase tracking-widest mb-1">Prioritas</p>
            <p class="text-3xl font-black text-gray-800">{{ $stats['prioritas'] }} <span class="text-sm font-medium text-gray-400">Warga</span></p>
        </div>
        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500 text-xl">
            <i class="fas fa-exclamation-circle"></i>
        </div>
    </div>
    <div class="bg-white border-l-4 border-l-green-500 border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-green-500 font-bold uppercase tracking-widest mb-1">Layak</p>
            <p class="text-3xl font-black text-gray-800">{{ $stats['layak'] }} <span class="text-sm font-medium text-gray-400">Warga</span></p>
        </div>
        <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500 text-xl">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>
    <div class="bg-white border-l-4 border-l-gray-400 border border-gray-100 rounded-2xl p-5 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-1">Tidak Layak</p>
            <p class="text-3xl font-black text-gray-800">{{ $stats['tidak_layak'] }} <span class="text-sm font-medium text-gray-400">Warga</span></p>
        </div>
        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 text-xl">
            <i class="fas fa-times-circle"></i>
        </div>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('hasil.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Periode</label>
            <select name="periode" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                @foreach($periodes as $p)
                <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-semibold text-gray-500 mb-1 block">Status</label>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                <option value="">Semua Status</option>
                <option value="prioritas" {{ $status === 'prioritas' ? 'selected' : '' }}>Prioritas</option>
                <option value="layak" {{ $status === 'layak' ? 'selected' : '' }}>Layak</option>
                <option value="tidak_layak" {{ $status === 'tidak_layak' ? 'selected' : '' }}>Tidak Layak</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20">
            <i class="fas fa-filter mr-1.5"></i> Filter
        </button>
        <a href="{{ route('saw.index') }}" class="px-5 py-2.5 bg-blue-50 text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-100 transition-all">
            <i class="fas fa-calculator mr-1.5"></i> Hitung Ulang
        </a>
        <a href="{{ route('simulasi.index') }}" class="px-5 py-2.5 bg-purple-50 text-purple-600 text-sm font-semibold rounded-xl hover:bg-purple-100 transition-all">
            <i class="fas fa-sliders mr-1.5"></i> Simulasi Bobot
        </a>
        <a href="{{ route('hasil.export-pdf', ['periode' => $periode]) }}"
           class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-red-500/20 ml-auto flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Ranking Warga — Periode {{ $periode }}</h3>
        <span class="text-xs text-gray-400">{{ $hasils->total() }} warga terdaftar</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50">
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-16">Rank</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Warga</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">RT/RW</th>
                <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Skor V</th>
                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($hasils as $h)
                <tr class="table-row-hover {{ $h->status === 'prioritas' ? 'bg-red-50/50' : '' }}">
                    <td class="px-6 py-4 text-center">
                        @if($h->ranking <= 3)
                            <span class="w-8 h-8 inline-flex items-center justify-center rounded-full text-xs font-bold shadow-sm {{ $h->ranking == 1 ? 'bg-amber-400 text-white' : ($h->ranking == 2 ? 'bg-gray-200 text-gray-700' : 'bg-amber-600 text-white') }}">
                                {{ $h->ranking }}
                            </span>
                        @else
                            <span class="text-gray-400 font-bold text-sm">{{ $h->ranking }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ $h->warga->nama }}
                        @if($h->status === 'prioritas')
                            <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold">PRIORITAS</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">RT {{ $h->warga->rt }} / RW {{ $h->warga->rw }}</td>
                    <td class="px-6 py-4 text-right font-black text-lg {{ $h->status === 'prioritas' ? 'text-red-600' : 'text-blue-700' }}">
                        {{ number_format($h->skor_akhir, 4) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($h->status === 'prioritas')
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-red-200">Prioritas</span>
                        @elseif($h->status === 'layak')
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-green-200">Layak</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200">Tidak Layak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                    <i class="fas fa-trophy mb-3 text-3xl text-gray-200 block"></i>
                    Belum ada hasil ranking untuk periode {{ $periode }}.
                    <a href="{{ route('saw.index') }}" class="text-blue-500 hover:underline block mt-1 text-xs">Hitung SAW sekarang →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($hasils->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $hasils->links() }}</div>
    @endif
</div>

@endsection
