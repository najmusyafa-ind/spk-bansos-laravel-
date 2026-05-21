@extends('layouts.admin')
@section('title', 'Import Data Warga')
@section('page_title', 'Import Data Warga')
@section('page_subtitle', 'Upload file CSV atau Excel untuk menginput data warga secara massal')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-start gap-3">
    <i class="fas fa-circle-check text-green-500 mt-0.5"></i>
    <div class="text-sm font-medium">
        <p>{{ session('success') }}</p>
        <a href="{{ route('saw.index', ['periode' => session('import_periode')]) }}" class="text-green-700 underline mt-1 inline-block">Lanjutkan ke Hitung SAW →</a>
    </div>
</div>
@endif

@if(session('import_errors') && count(session('import_errors')) > 0)
<div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl text-sm max-h-64 overflow-y-auto">
    <p class="font-bold mb-2"><i class="fas fa-triangle-exclamation mr-1.5"></i>Terdapat baris yang dilewati karena error:</p>
    <ul class="list-disc list-inside space-y-1 text-xs">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-start gap-3">
    <i class="fas fa-triangle-exclamation text-red-500 mt-0.5"></i>
    <div class="text-sm font-medium">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Form Upload --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Upload File Data Warga</h3>
        
        <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Periode Penilaian <span class="text-red-500">*</span></label>
                <input type="month" name="periode" value="{{ old('periode', $periodeAktif) }}" required
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
                <p class="text-xs text-gray-400 mt-1">Data yang diimpor akan masuk ke periode ini.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">File CSV <span class="text-red-500">*</span></label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition-colors relative">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-file-csv text-4xl text-gray-300 mb-3"></i>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-semibold text-[#1e2d7a] hover:text-blue-800 focus-within:outline-none">
                                <span>Pilih file CSV</span>
                                <input id="file-upload" name="file" type="file" class="sr-only" accept=".csv" required onchange="updateFileName(this)">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">Maksimum 5MB</p>
                    </div>
                </div>
                <p id="file-name" class="text-xs font-semibold text-green-600 mt-2 hidden text-center"></p>
            </div>


            <button type="submit" class="w-full py-3 bg-[#1e2d7a] hover:bg-[#172264] text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-blue-900/20 hover:-translate-y-0.5">
                <i class="fas fa-upload mr-2"></i> Proses Import Data
            </button>
        </form>
    </div>

    {{-- Panduan & Template --}}
    <div class="space-y-6">
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
            <h3 class="font-bold text-[#1e2d7a] mb-3">
                <i class="fas fa-download mr-1.5"></i> Download File CSV
            </h3>
            <p class="text-sm text-blue-800 mb-4 leading-relaxed">
                Gunakan template CSV kosong atau gunakan data dummy berisi 10 warga yang sudah siap untuk disimulasikan ke dalam sistem.
            </p>
            <div class="flex flex-col gap-3">
                <a href="{{ asset('template-import.csv') }}" download
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-[#1e2d7a] font-semibold text-sm rounded-xl border border-blue-200 hover:bg-blue-50 transition-all shadow-sm">
                    <i class="fas fa-file-csv"></i> Download Template Kosong
                </a>
                <a href="{{ asset('data-warga-dummy.csv') }}" download
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1e2d7a] text-white font-semibold text-sm rounded-xl hover:bg-[#172264] transition-all shadow-md shadow-blue-900/20">
                    <i class="fas fa-file-import"></i> Download 10 Data Warga (Dummy)
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-3">Aturan Pengisian</h3>
            <ul class="text-xs text-gray-600 space-y-2 list-disc list-inside">
                <li><strong class="text-gray-800">nama</strong>: Nama lengkap warga. Jika nama sudah ada, data akan di-update.</li>
                <li><strong class="text-gray-800">penghasilan</strong>: Kurang dari 1 juta / 1-2 juta / 2-3 juta / Lebih dari 3 juta</li>
                <li><strong class="text-gray-800">tanggungan</strong>: 1 orang / 2-3 orang / 4-5 orang / >= 6 orang</li>
                <li><strong class="text-gray-800">kondisi_rumah</strong>: Sangat buruk / Buruk / Sedang / Baik</li>
                <li><strong class="text-gray-800">pekerjaan</strong>: Tidak bekerja / Tidak tetap / Tetap/PNS</li>
                <li><strong class="text-gray-800">aset</strong>: Tidak ada / Sedikit / Banyak</li>
            </ul>
            <p class="text-[10px] text-gray-400 mt-4 italic">
                * Sistem akan otomatis memetakan teks yang mirip (case-insensitive). Jika tidak dikenali, nilai otomatis diset 1.
            </p>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    function updateFileName(input) {
        const fileNameElement = document.getElementById('file-name');
        if (input.files && input.files.length > 0) {
            fileNameElement.textContent = `File terpilih: ${input.files[0].name}`;
            fileNameElement.classList.remove('hidden');
        } else {
            fileNameElement.textContent = '';
            fileNameElement.classList.add('hidden');
        }
    }
</script>
@endsection
