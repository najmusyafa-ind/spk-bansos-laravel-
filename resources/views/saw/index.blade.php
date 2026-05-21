@extends('layouts.admin')
@section('title', 'Hitung SAW')
@section('page_title', 'Hitung SAW')
@section('page_subtitle', 'Jalankan kalkulasi Simple Additive Weighting untuk periode yang dipilih')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3">
    <i class="fas fa-circle-check text-green-500"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

@if($errors->has('saw'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-start gap-3">
    <i class="fas fa-triangle-exclamation text-red-500 mt-0.5"></i>
    <span class="text-sm font-medium">{{ $errors->first('saw') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form Hitung --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Jalankan Kalkulasi</h3>
        <p class="text-xs text-gray-400 mb-5">Pilih periode dan klik tombol hitung untuk memproses ranking warga.</p>

        <form method="POST" action="{{ route('saw.hitung') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Periode <span class="text-red-500">*</span></label>
                <input type="month" name="periode" value="{{ old('periode', $periodeAktif) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                <p class="text-xs text-gray-400 mt-1">Format: YYYY-MM (misal: 2026-04)</p>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-500/30 hover:-translate-y-0.5">
                <i class="fas fa-calculator mr-2"></i> Hitung SAW Sekarang
            </button>
        </form>

        <div class="mt-5 pt-5 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-500 mb-3">Periode tersedia:</p>
            @forelse($periodes as $p)
            <a href="{{ route('saw.index', ['periode' => $p]) }}"
               class="block px-4 py-2.5 rounded-xl text-xs font-semibold mb-2 transition-all {{ $periode === $p ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent' }}">
                <i class="fas fa-calendar-alt mr-1.5 {{ $periode === $p ? 'text-blue-500' : 'text-gray-400' }}"></i> {{ $p }}
            </a>
            @empty
            <p class="text-xs text-gray-400 italic">Belum ada data penilaian.</p>
            @endforelse
        </div>
    </div>

    {{-- Preview Hasil --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Hasil Kalkulasi — Periode {{ $periode }}</h3>
            <a href="{{ route('hasil.index', ['periode' => $periode]) }}" class="text-xs text-blue-600 hover:underline">Lihat Detail →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-16">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Warga</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Skor V</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($hasils as $h)
                    <tr class="table-row-hover {{ $h->status === 'prioritas' ? 'bg-red-50/50' : '' }}">
                        <td class="px-6 py-4">
                            @if($h->ranking <= 3)
                                <span class="w-8 h-8 inline-flex items-center justify-center rounded-full text-xs font-bold shadow-sm {{ $h->ranking == 1 ? 'bg-amber-400 text-white' : ($h->ranking == 2 ? 'bg-gray-200 text-gray-700' : 'bg-amber-600 text-white') }}">
                                    {{ $h->ranking }}
                                </span>
                            @else
                                <span class="text-gray-400 font-semibold text-sm ml-2">{{ $h->ranking }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $h->warga->nama }}
                            @if($h->status === 'prioritas')
                                <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold">PRIORITAS</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-black {{ $h->status === 'prioritas' ? 'text-red-600' : 'text-blue-700' }}">{{ number_format($h->skor_akhir, 4) }}</td>
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
                    <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                        <i class="fas fa-calculator mb-3 text-3xl text-gray-200 block"></i>
                        Belum ada hasil untuk periode ini. Klik "Hitung SAW Sekarang" untuk memulai.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
