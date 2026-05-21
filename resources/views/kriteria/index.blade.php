@extends('layouts.admin')
@section('title', 'Data Kriteria')
@section('page_title', 'Data Kriteria')
@section('page_subtitle', 'Kelola kriteria penilaian SPK — perubahan tipe/kode memerlukan hitung ulang AHP')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3">
    <i class="fas fa-circle-check text-green-500"></i>
    <span class="text-sm font-medium">{{ session('success') }}</span>
</div>
@endif

@if($errors->has('delete'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
    <i class="fas fa-triangle-exclamation text-red-500"></i>
    <span class="text-sm font-medium">{{ $errors->first('delete') }}</span>
</div>
@endif

{{-- Action bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex gap-3">
        <a href="{{ route('kriteria.create') }}"
           class="px-5 py-2.5 bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kriteria
        </a>
        <form method="POST" action="{{ route('kriteria.reset-bobot') }}"
              onsubmit="return confirm('Reset semua bobot AHP? Anda harus mengisi ulang matriks perbandingan.')">
            @csrf
            <button type="submit"
                    class="px-5 py-2.5 bg-orange-50 hover:bg-orange-100 text-orange-700 font-semibold text-sm rounded-xl border border-orange-200 transition-all flex items-center gap-2">
                <i class="fas fa-rotate-left"></i> Reset Semua Bobot AHP
            </button>
        </form>
    </div>
    <a href="{{ route('ahp.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
        <i class="fas fa-sliders text-xs"></i> Ke Matriks AHP →
    </a>
</div>

{{-- Info Banner --}}
<div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-3.5 mb-6 flex items-start gap-3">
    <i class="fas fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
    <p class="text-xs text-amber-700 leading-relaxed">
        <strong>Perhatian:</strong> Jika Anda mengubah <strong>tipe kriteria</strong> (benefit ↔ cost) atau <strong>menghapus kriteria</strong>,
        bobot AHP akan di-reset otomatis dan Anda harus mengisi ulang matriks perbandingan sebelum menjalankan kalkulasi SAW.
    </p>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-20">Urutan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-24">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Kriteria</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Bobot (AHP)</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($kriterias as $k)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center">
                        <span class="w-7 h-7 inline-flex items-center justify-center bg-[#1e2d7a] text-white text-xs font-bold rounded-lg">{{ $k->urutan }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-black text-[#1e2d7a] text-base">{{ $k->kode }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $k->nama }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase {{ $k->tipe === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $k->tipe }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($k->bobot)
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-[#1e2d7a] h-1.5 rounded-full" style="width: {{ min($k->bobot * 100 * 2, 100) }}%"></div>
                                </div>
                                <span class="font-bold text-blue-700 text-xs">{{ number_format($k->bobot, 4) }}</span>
                            </div>
                        @else
                            <span class="text-gray-300 text-xs italic">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">{{ $k->deskripsi ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('kriteria.edit', $k) }}"
                               class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                <i class="fas fa-pen text-[10px]"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('kriteria.destroy', $k) }}"
                                  onsubmit="return confirm('Hapus kriteria {{ $k->kode }} ({{ $k->nama }})? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1">
                                    <i class="fas fa-trash text-[10px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                        <i class="fas fa-list-check mb-3 text-3xl text-gray-200 block"></i>
                        Belum ada kriteria.
                        <a href="{{ route('kriteria.create') }}" class="text-blue-500 hover:underline ml-1">Tambahkan sekarang.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
        <span class="text-xs text-gray-400">Total bobot: <strong class="text-gray-700">{{ number_format($kriterias->sum('bobot'), 4) }}</strong> (idealnya = 1.0000)</span>
        <span class="text-xs text-gray-400">{{ $kriterias->count() }} kriteria terdaftar</span>
    </div>
</div>

@endsection
