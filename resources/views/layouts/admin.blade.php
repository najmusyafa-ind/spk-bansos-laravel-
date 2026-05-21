<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Bansos') — SPK Bantuan Sosial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-active {
            background-color: #eff6ff;
            border-right: 4px solid #2563eb;
            color: #1d4ed8;
            font-weight: 700;
        }

        .metric-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .metric-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 15px 35px -5px rgba(37, 99, 235, 0.1);
            border-color: #bfdbfe;
        }

        .table-row-hover {
            transition: all 0.2s ease;
        }

        .table-row-hover:hover {
            background-color: #f8fafc;
            trnsform: scale(1.002);
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            border-color: #3b82f6;
            transition: all 0.2s ease;
        }

        .btn-hover {
            transition: all 0.2s ease;
        }

        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-50 text-gray-800">
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 h-full overflow-y-auto">
            {{-- Logo --}}
            <div class="px-6 py-8 border-b border-gray-100 flex items-center gap-4">
                <img src="{{ asset('logo.png') }}" alt="Logo Purbalingga" class="w-12 h-auto drop-shadow-sm">
                <div>
                    <h1 class="text-gray-900 font-bold text-base leading-tight">SPK Bansos</h1>
                    <p class="text-gray-500 text-[10px] leading-tight font-medium uppercase tracking-wider mt-0.5">Kab.
                        Purbalingga</p>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-5 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Dashboard'],
                        ['route' => 'kriteria.index', 'icon' => 'fa-list-check', 'label' => 'Data Kriteria'],
                        ['route' => 'ahp.index', 'icon' => 'fa-sliders', 'label' => 'Matriks AHP'],
                        ['route' => 'warga.index', 'icon' => 'fa-users', 'label' => 'Data Warga'],
                        ['route' => 'import.index', 'icon' => 'fa-file-import', 'label' => 'Import CSV'],
                        ['route' => 'saw.index', 'icon' => 'fa-calculator', 'label' => 'Hitung SAW'],
                        ['route' => 'hasil.index', 'icon' => 'fa-trophy', 'label' => 'Hasil Ranking'],
                        ['route' => 'simulasi.index', 'icon' => 'fa-chart-line', 'label' => 'Simulasi Bobot'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']) || str_starts_with(request()->route()?->getName() ?? '', explode('.', $item['route'])[0]); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all
                                          {{ $isActive ? 'sidebar-active' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i
                            class="fas {{ $item['icon'] }} w-5 text-center {{ $isActive ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- User + Logout --}}
            <div class="p-4 border-t border-gray-100">
                <div class="px-4 py-3 bg-gray-50 rounded-xl mb-3 border border-gray-100 flex items-center gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-gray-800 text-xs font-bold truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                        <p class="text-gray-500 text-[10px] capitalize font-medium">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-all border border-red-100">
                        <i class="fas fa-right-from-bracket w-4"></i>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Topbar --}}
            <header
                class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between flex-shrink-0 z-10 shadow-sm">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">@yield('page_title', 'Dashboard')</h2>
                    <p class="text-xs text-gray-500 mt-1 font-medium">
                        @yield('page_subtitle', 'Sistem Pendukung Keputusan Bantuan Sosial')</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-blue-500"></i>
                        <span class="text-gray-700 text-xs font-semibold">
                            {{ now()->format('F Y') }}
                        </span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div
                        class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                        <i class="fas fa-circle-check text-green-500 mt-0.5"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->has('cr') || $errors->has('saw'))
                    <div
                        class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-start gap-3 shadow-sm">
                        <i class="fas fa-triangle-exclamation text-red-500 mt-0.5"></i>
                        <div class="text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Global SweetAlert2 Configuration for Delete Actions
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.form-delete');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="fas fa-trash mr-1.5"></i>Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl shadow-xl border border-gray-100',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm shadow-md',
                            cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>