<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Bantuan Sosial - AHP & SAW</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="antialiased text-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <header class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-blue-700">Sistem Pendukung Keputusan</h1>
                <p class="text-gray-500 mt-2">Penerima Bantuan Sosial (Metode AHP & SAW)</p>
            </div>
            <div>
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full border border-blue-200">Proyek
                    SPK - Pak Hasirun</span>
            </div>
        </header>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-8 shadow-sm rounded-r-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($kriterias->isEmpty())
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 shadow-sm rounded-r-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 font-medium">
                            Data Kriteria atau Warga belum tersedia.
                        </p>
                        <p class="text-sm text-yellow-600 mt-1">
                            Sistem memerlukan setidaknya 1 Kriteria dan 1 Data Warga beserta nilai penilainnya agar dapat
                            melakukan perhitungan SPK. Silakan isi data tersebut ke database terlebih dahulu (melalui
                            Database Seeder atau form input).
                        </p>
                    </div>
                </div>
            </div>
        @else

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Data Kriteria -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Data Kriteria & Bobot (AHP)</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Kode</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama Kriteria</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Sifat</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Bobot</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($kriterias as $k)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">{{ $k->kode }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $k->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md {{ $k->sifat == 'Benefit' ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
                                                {{ $k->sifat }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                            {{ number_format($k->bobot, 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Warga -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Data Warga (Calon Penerima)</h2>
                        <a href="{{ route('warga.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Warga
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Alamat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($wargas as $w)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $w->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $w->alamat ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-400 text-center italic">Belum
                                            ada data warga terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(!empty($results))
                <!-- Hasil Ranking -->
                <div class="bg-white rounded-xl shadow-md border border-blue-100 overflow-hidden mt-8 ring-1 ring-blue-50">
                    <div
                        class="px-6 py-5 border-b border-blue-100 bg-gradient-to-r from-blue-50 to-white flex items-center justify-between">
                        <h2 class="text-xl font-bold text-blue-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                </path>
                            </svg>
                            Hasil Pemeringkatan (SAW)
                        </h2>
                        <span class="text-sm text-blue-600 font-medium">Diurutkan dari nilai tertinggi</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">
                                        Peringkat</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nama Warga</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nilai Preferensi (V)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($results as $res)
                                    <tr class="{{ $loop->first ? 'bg-amber-50/50' : 'hover:bg-gray-50' }} transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($loop->first)
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-white font-bold shadow-sm ring-2 ring-white">
                                                    1
                                                </span>
                                            @elseif($loop->iteration == 2)
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-700 font-bold shadow-sm ring-2 ring-white">
                                                    2
                                                </span>
                                            @elseif($loop->iteration == 3)
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold shadow-sm ring-2 ring-white">
                                                    3
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-bold">
                                                    {{ $res['ranking'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $loop->first ? 'text-amber-700 text-base' : 'text-gray-800' }}">
                                            {{ $res['warga']->nama }}
                                            @if($loop->first)
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                    Prioritas Utama
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-black {{ $loop->first ? 'text-green-600 text-lg' : 'text-blue-600' }}">
                                            {{ number_format($res['total_v'], 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        @endif

        <footer class="mt-12 text-center text-sm text-gray-400 pb-8 border-t border-gray-200 pt-8">
            &copy; {{ date('Y') }} Sistem Pendukung Keputusan. Dibuat untuk memenuhi Tugas Pak Hasirun.
        </footer>
    </div>
</body>

</html>