@extends('layouts.admin')
@section('title', 'Edit Kriteria ' . $kriteria->kode)
@section('page_title', 'Edit Kriteria ' . $kriteria->kode)
@section('page_subtitle', 'Ubah nama, tipe, atau deskripsi kriteria')

@section('content')
@if($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl text-sm">
    <p class="font-bold mb-2"><i class="fas fa-triangle-exclamation mr-1.5"></i>Terdapat kesalahan:</p>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl mx-auto">
    @if($kriteria->bobot)
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl px-5 py-3.5 flex items-start gap-3">
        <i class="fas fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
        <p class="text-xs text-amber-700 leading-relaxed">
            Kriteria ini sudah memiliki bobot AHP = <strong>{{ number_format($kriteria->bobot, 4) }}</strong>.
            Jika Anda mengubah <strong>tipe</strong>, bobot akan di-reset dan Anda perlu mengisi ulang matriks AHP.
        </p>
    </div>
    @endif

    <form method="POST" action="{{ route('kriteria.update', $kriteria) }}" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Detail Kriteria</h3>
                <span class="text-sm font-black text-[#1e2d7a] bg-blue-50 px-3 py-1 rounded-lg">{{ $kriteria->kode }}</span>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode</label>
                        <div class="w-full px-4 py-2.5 border border-gray-100 rounded-xl text-sm bg-gray-100 text-gray-500 font-bold">{{ $kriteria->kode }}</div>
                        <p class="text-xs text-gray-400 mt-1">Kode tidak dapat diubah</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" value="{{ old('urutan', $kriteria->urutan) }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $kriteria->nama) }}" required maxlength="100"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Kriteria <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-green-300 transition-colors {{ old('tipe', $kriteria->tipe) === 'benefit' ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                            <input type="radio" name="tipe" value="benefit" {{ old('tipe', $kriteria->tipe) === 'benefit' ? 'checked' : '' }} required class="mt-0.5">
                            <div>
                                <p class="text-sm font-bold text-green-700">Benefit</p>
                                <p class="text-xs text-gray-500 mt-0.5">Nilai tinggi = lebih baik</p>
                            </div>
                        </label>
                        <label class="relative flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-red-300 transition-colors {{ old('tipe', $kriteria->tipe) === 'cost' ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                            <input type="radio" name="tipe" value="cost" {{ old('tipe', $kriteria->tipe) === 'cost' ? 'checked' : '' }} required class="mt-0.5">
                            <div>
                                <p class="text-sm font-bold text-red-700">Cost</p>
                                <p class="text-xs text-gray-500 mt-0.5">Nilai rendah = lebih baik</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white resize-none">{{ old('deskripsi', $kriteria->deskripsi) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('kriteria.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-all">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20">
                <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
