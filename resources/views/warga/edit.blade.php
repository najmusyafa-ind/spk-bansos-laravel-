@extends('layouts.admin')
@section('title', 'Edit Warga')
@section('page_title', 'Edit Data Warga')
@section('page_subtitle', 'Perbarui data diri dan nilai kriteria warga periode {{ $periodeAktif }}')

@section('content')

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl text-sm">
            <p class="font-bold mb-2"><i class="fas fa-triangle-exclamation mr-1.5"></i>Terdapat kesalahan pada input:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('warga.update', $warga) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Data Diri --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-id-card text-[#1e2d7a] mr-2"></i>1. Data Diri Warga</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $warga->nama) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                        placeholder="Nama sesuai KTP">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">RT <span class="text-red-500">*</span></label>
                        <input type="text" name="rt" value="{{ old('rt', $warga->rt) }}" maxlength="5" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                            placeholder="001">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">RW <span class="text-red-500">*</span></label>
                        <input type="text" name="rw" value="{{ old('rw', $warga->rw) }}" maxlength="5" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                            placeholder="002">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelurahan <span class="text-red-500">*</span></label>
                    <input type="text" name="kelurahan" value="{{ old('kelurahan', $warga->kelurahan) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white"
                        placeholder="Nama kelurahan">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white resize-none"
                        placeholder="Jl. ...">{{ old('alamat', $warga->alamat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Penilaian Kriteria --}}
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-blue-100 bg-blue-50">
                <h3 class="font-bold text-[#1e2d7a]"><i class="fas fa-star-half-alt mr-2"></i>2. Penilaian Kriteria</h3>
                <p class="text-xs text-blue-500 mt-0.5">Nilai periode <strong>{{ $periodeAktif }}</strong>. Perubahan hanya berlaku untuk periode ini.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                    @php
                        $kriteriaCols = [
                            'C1' => ['label' => 'Penghasilan',   'field' => 'penghasilan', 'color' => 'bg-[#1e2d7a]', 'tipe' => 'Cost — Rendah lebih baik',   'tipe_color' => 'text-red-500',   'opts' => ['Kurang dari 1 juta','1-2 juta','2-3 juta','Lebih dari 3 juta']],
                            'C2' => ['label' => 'Tanggungan',    'field' => 'tanggungan',  'color' => 'bg-indigo-600', 'tipe' => 'Benefit — Banyak lebih baik', 'tipe_color' => 'text-green-600', 'opts' => ['1 orang','2-3 orang','4-5 orang','>= 6 orang']],
                            'C3' => ['label' => 'Kondisi Rumah', 'field' => 'rumah',       'color' => 'bg-purple-600', 'tipe' => 'Benefit — Buruk diprioritaskan','tipe_color' => 'text-green-600','opts' => ['Sangat buruk','Buruk','Sedang','Baik']],
                            'C4' => ['label' => 'Pekerjaan',     'field' => 'pekerjaan',   'color' => 'bg-teal-600',   'tipe' => 'Benefit — Tidak bekerja diprioritaskan','tipe_color'=>'text-green-600','opts'=>['Tidak bekerja','Tidak tetap','Tetap/PNS']],
                            'C5' => ['label' => 'Aset',          'field' => 'aset',        'color' => 'bg-orange-500', 'tipe' => 'Cost — Tidak ada diprioritaskan','tipe_color' => 'text-red-500', 'opts' => ['Tidak ada','Sedikit','Banyak']],
                        ];
                    @endphp

                    @foreach($kriteriaCols as $kode => $cfg)
                        @php
                            $kriteriaObj  = $kriterias->firstWhere('kode', $kode);
                            $currentRaw   = $kriteriaObj ? ($penilaians[$kriteriaObj->id]->nilai_raw ?? '') : '';
                            $currentRaw   = old($cfg['field'], $currentRaw);
                        @endphp
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 hover:border-blue-200 transition-colors">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="w-7 h-7 {{ $cfg['color'] }} text-white text-xs font-bold rounded-lg flex items-center justify-center">{{ $kode }}</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $cfg['label'] }}</p>
                                    <p class="text-[10px] {{ $cfg['tipe_color'] }} font-semibold uppercase">{{ $cfg['tipe'] }}</p>
                                </div>
                            </div>
                            <select name="{{ $cfg['field'] }}" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:border-blue-500 cursor-pointer">
                                <option value="">-- Pilih --</option>
                                @foreach($cfg['opts'] as $opt)
                                    <option value="{{ $opt }}" {{ strcasecmp($currentRaw, $opt) === 0 ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach

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
                <i class="fas fa-save mr-1.5"></i> Simpan Perubahan
            </button>
        </div>
    </form>

@endsection
