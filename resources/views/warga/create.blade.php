@extends('layouts.admin')
@section('title', 'Tambah Warga')
@section('page_title', 'Tambah Data Warga')
@section('page_subtitle', 'Input data calon penerima bansos beserta nilai setiap kriteria')

@section('content')

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl text-sm">
            <p class="font-bold mb-2"><i class="fas fa-triangle-exclamation mr-1.5"></i>Terdapat kesalahan pada input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('warga.store') }}" class="space-y-6">
        @csrf

        {{-- Data Diri --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-id-card text-[#1e2d7a] mr-2"></i>1. Data Diri Warga
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                        placeholder="Nama sesuai KTP">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">RT <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="rt" value="{{ old('rt') }}" maxlength="5" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                            placeholder="001">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">RW <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="rw" value="{{ old('rw') }}" maxlength="5" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                            placeholder="002">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelurahan <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', 'Sukamaju') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                        placeholder="Nama kelurahan">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white resize-none"
                        placeholder="Jl. ...">{{ old('alamat') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Penilaian Kriteria --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-100 bg-blue-50">
                <h3 class="font-bold text-[#1e2d7a]"><i class="fas fa-star-half-alt mr-2"></i>2. Penilaian Kriteria</h3>
                <p class="text-xs text-blue-500 mt-0.5">Pilih kondisi yang paling sesuai untuk warga ini pada setiap
                    kriteria.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                    {{-- C1: Penghasilan --}}
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 bg-[#1e2d7a] text-white text-xs font-bold rounded-lg flex items-center justify-center">C1</span>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Penghasilan</p>
                                <p class="text-[10px] text-red-500 font-semibold uppercase">Cost — Rendah lebih baik</p>
                            </div>
                        </div>
                        <select name="penghasilan" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @foreach(['Kurang dari 1 juta', '1-2 juta', '2-3 juta', 'Lebih dari 3 juta'] as $opt)
                                <option value="{{ $opt }}" {{ old('penghasilan') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- C2: Tanggungan --}}
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 bg-indigo-600 text-white text-xs font-bold rounded-lg flex items-center justify-center">C2</span>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Tanggungan</p>
                                <p class="text-[10px] text-green-600 font-semibold uppercase">Benefit — Banyak lebih baik
                                </p>
                            </div>
                        </div>
                        <select name="tanggungan" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @foreach(['1 orang', '2-3 orang', '4-5 orang', '>= 6 orang'] as $opt)
                                <option value="{{ $opt }}" {{ old('tanggungan') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- C3: Kondisi Rumah --}}
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 bg-purple-600 text-white text-xs font-bold rounded-lg flex items-center justify-center">C3</span>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Kondisi Rumah</p>
                                <p class="text-[10px] text-green-600 font-semibold uppercase">Benefit — Buruk lebih
                                    diprioritaskan</p>
                            </div>
                        </div>
                        <select name="rumah" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @foreach(['Sangat buruk', 'Buruk', 'Sedang', 'Baik'] as $opt)
                                <option value="{{ $opt }}" {{ old('rumah') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- C4: Pekerjaan --}}
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 bg-teal-600 text-white text-xs font-bold rounded-lg flex items-center justify-center">C4</span>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Pekerjaan</p>
                                <p class="text-[10px] text-green-600 font-semibold uppercase">Benefit — Tidak bekerja lebih
                                    diprioritaskan</p>
                            </div>
                        </div>
                        <select name="pekerjaan" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @foreach(['Tidak bekerja', 'Tidak tetap', 'Tetap/PNS'] as $opt)
                                <option value="{{ $opt }}" {{ old('pekerjaan') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- C5: Aset --}}
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="w-7 h-7 bg-orange-500 text-white text-xs font-bold rounded-lg flex items-center justify-center">C5</span>
                            <div>
                                <p class="text-sm font-bold text-gray-800">Aset</p>
                                <p class="text-[10px] text-red-500 font-semibold uppercase">Cost — Tidak ada lebih
                                    diprioritaskan</p>
                            </div>
                        </div>
                        <select name="aset" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                            <option value="">-- Pilih --</option>
                            @foreach(['Tidak ada', 'Sedikit', 'Banyak'] as $opt)
                                <option value="{{ $opt }}" {{ old('aset') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('warga.index') }}"
                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-all">
                Batal
            </a>
            <button type="submit"
                class="btn-hover px-8 py-2.5 bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20">
                <i class="fas fa-save mr-1.5"></i> Simpan Data Warga
            </button>
        </div>
    </form>

@endsection