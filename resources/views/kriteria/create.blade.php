@extends('layouts.admin')
@section('title', 'Tambah Kriteria')
@section('page_title', 'Tambah Kriteria Baru')
@section('page_subtitle', 'Tambah kriteria penilaian yang akan digunakan dalam AHP dan SAW')

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
    <form method="POST" action="{{ route('kriteria.store') }}" class="space-y-6">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Detail Kriteria</h3>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode <span class="text-red-500">*</span></label>
                        <input type="text" name="kode" value="{{ old('kode') }}" required maxlength="10" placeholder="C1, C2, ..."
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white font-bold uppercase">
                        <p class="text-xs text-gray-400 mt-1">Contoh: C1, C2, C6</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                        <input type="number" name="urutan" value="{{ old('urutan') }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                               placeholder="1, 2, 3, ...">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kriteria <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="100"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                           placeholder="Mis: Penghasilan Bulanan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Kriteria <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-green-300 transition-colors {{ old('tipe') === 'benefit' ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-gray-50' }}">
                            <input type="radio" name="tipe" value="benefit" {{ old('tipe') === 'benefit' ? 'checked' : '' }} required class="mt-0.5">
                            <div>
                                <p class="text-sm font-bold text-green-700">Benefit</p>
                                <p class="text-xs text-gray-500 mt-0.5">Nilai tinggi = lebih baik<br>(Tanggungan, Kondisi Rumah, Pekerjaan)</p>
                            </div>
                        </label>
                        <label class="relative flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer hover:border-red-300 transition-colors {{ old('tipe') === 'cost' ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                            <input type="radio" name="tipe" value="cost" {{ old('tipe') === 'cost' ? 'checked' : '' }} required class="mt-0.5">
                            <div>
                                <p class="text-sm font-bold text-red-700">Cost</p>
                                <p class="text-xs text-gray-500 mt-0.5">Nilai rendah = lebih baik<br>(Penghasilan, Aset)</p>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white resize-none"
                              placeholder="Penjelasan singkat tentang kriteria ini...">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('kriteria.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-all">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20">
                <i class="fas fa-save mr-1.5"></i> Simpan Kriteria
            </button>
        </div>
    </form>
</div>
@endsection
