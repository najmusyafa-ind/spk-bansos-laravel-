<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Bansos') — SPK Bantuan Sosial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        /* ===== LAYOUT ===== */
        body {
            margin: 0;
            background: #f1f5f9;
            color: #1e293b;
        }

        .app-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            width: 270px;
            min-width: 270px;
            background: #fff;
            border-right: 1px solid #e8edf3;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
        }

        /* Mobile: sidebar hidden by default, fixed overlay */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%);
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.15);
            }

            #sidebar.open {
                transform: translateX(0);
            }
        }

        /* Desktop: sidebar always visible, part of flex flow */
        @media (min-width: 1024px) {
            #sidebar {
                position: relative;
                transform: none !important;
                flex-shrink: 0;
            }

            #mobile-topbar {
                display: none !important;
            }

            #sidebar-overlay {
                display: none !important;
            }
        }

        /* ===== OVERLAY ===== */
        #sidebar-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 40;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        #sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        /* ===== MAIN ===== */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            scroll-behavior: smooth;
        }

        @media (min-width: 768px) {
            .main-content {
                padding: 2rem;
            }
        }

        /* ===== SIDEBAR NAV STYLES ===== */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .nav-link.active {
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: 700;
            box-shadow: inset 3px 0 0 #2563eb;
        }

        .nav-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background: #f1f5f9;
            color: #94a3b8;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .nav-link.active .nav-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .nav-link:hover:not(.active) .nav-icon {
            background: #e0e7ff;
            color: #4f46e5;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
        }

        /* Mobile topbar - must be above sidebar and overlay */
        #mobile-topbar {
            padding: 12px 16px;
            z-index: 60 !important;
        }

        /* Desktop topbar hidden on mobile via CSS */
        #desktop-topbar {
            display: none;
        }

        @media (min-width: 1024px) {
            #mobile-topbar {
                display: none !important;
            }

            #desktop-topbar {
                display: flex !important;
            }
        }

        /* ===== HAMBURGER ===== */
        #hamburger {
            background: none;
            border: none;
            cursor: pointer;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #475569;
            font-size: 18px;
            transition: all 0.2s;
        }

        #hamburger:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* ===== CARD METRIC ===== */
        .metric-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            padding: 22px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -8px rgba(37, 99, 235, 0.12);
        }

        /* ===== TABLE ROWS ===== */
        .table-row-hover {
            transition: background 0.15s;
        }

        .table-row-hover:hover {
            background: #f8fafc;
        }

        /* ===== FLASH MESSAGES ===== */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 16px 20px;
            border-radius: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
            animation: slideIn 0.4s ease;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 16px 20px;
            border-radius: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 24px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== SIDEBAR LOGO ===== */
        .sidebar-logo-wrap {
            padding: 24px 20px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 14px;
            background: linear-gradient(135deg, #eff6ff 0%, #fff 100%);
        }

        .sidebar-logo-wrap img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand h1 {
            font-size: 16px;
            font-weight: 900;
            background: linear-gradient(135deg, #1d4ed8, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0 0 2px 0;
        }

        .sidebar-brand p {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #93c5fd;
            margin: 0;
        }

        /* ===== SIDEBAR BOTTOM ===== */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #f1f5f9;
            margin-top: auto;
        }

        .user-card {
            background: #f8fafc;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #bfdbfe, #c7d2fe);
            color: #1d4ed8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
        }

        .user-role {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            font-weight: 600;
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid #fee2e2;
            background: #fff5f5;
            color: #dc2626;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }
    </style>
    @yield('styles')
</head>

<body>
    {{-- Overlay for mobile --}}
    <div id="sidebar-overlay"></div>

    <div class="app-wrapper">

        {{-- ===== SIDEBAR ===== --}}
        <aside id="sidebar">

            {{-- Logo --}}
            <div class="sidebar-logo-wrap">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Purbalingga"
                    onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/b/b8/Lambang_Kabupaten_Purbalingga.png/60px-Lambang_Kabupaten_Purbalingga.png'">
                <div class="sidebar-brand">
                    <h1>SPK Bansos</h1>
                    <p>Kab. Purbalingga</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav style="flex:1; padding: 18px 12px; overflow-y:auto;">
                <p
                    style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.12em; color:#cbd5e1; padding: 0 4px; margin: 0 0 10px 0;">
                    MENU UTAMA</p>
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Dashboard'],
                        ['route' => 'kriteria.index', 'icon' => 'fa-layer-group', 'label' => 'Data Kriteria'],
                        ['route' => 'ahp.index', 'icon' => 'fa-code-compare', 'label' => 'Matriks AHP'],
                        ['route' => 'warga.index', 'icon' => 'fa-users', 'label' => 'Data Warga'],
                        ['route' => 'import.index', 'icon' => 'fa-cloud-arrow-up', 'label' => 'Import CSV'],
                        ['route' => 'saw.index', 'icon' => 'fa-calculator', 'label' => 'Hitung SAW'],
                        ['route' => 'hasil.index', 'icon' => 'fa-trophy', 'label' => 'Hasil Ranking'],
                        ['route' => 'simulasi.index', 'icon' => 'fa-chart-line', 'label' => 'Simulasi Bobot'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route'])
                            || str_starts_with(request()->route()?->getName() ?? '', explode('.', $item['route'])[0]);
                    @endphp
                    <a href="{{ route($item['route']) }}" class="nav-link {{ $isActive ? 'active' : '' }}"
                        onclick="closeSidebar()">
                        <div class="nav-icon"><i class="fas {{ $item['icon'] }}"></i></div>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Footer --}}
            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div style="min-width:0">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== MAIN CONTAINER ===== --}}
        <div class="main-container">

            {{-- Mobile Topbar --}}
            <div id="mobile-topbar" class="topbar">
                <div style="display:flex; align-items:center; gap:10px;">
                    <button id="hamburger" onclick="openSidebar()" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <img src="{{ asset('img/logo.png') }}" alt="Logo"
                        style="width:30px; height:30px; object-fit:contain;"
                        onerror="this.src='https://upload.wikimedia.org/wikipedia/id/thumb/b/b8/Lambang_Kabupaten_Purbalingga.png/60px-Lambang_Kabupaten_Purbalingga.png'">
                    <span style="font-size:14px; font-weight:800; color:#1e293b;">SPK Bansos</span>
                </div>
                <div class="user-avatar" style="width:34px; height:34px; font-size:13px;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>

            {{-- Desktop Topbar --}}
            <div id="desktop-topbar" class="topbar">
                <div>
                    <h2 style="margin:0; font-size:22px; font-weight:900; color:#0f172a; letter-spacing:-0.02em;">
                        @yield('page_title', 'Dashboard')</h2>
                    <p style="margin:4px 0 0; font-size:13px; color:#94a3b8; font-weight:500;">
                        <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                        @yield('page_subtitle', 'Sistem Pendukung Keputusan Bantuan Sosial')
                    </p>
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:8px 14px; display:flex; align-items:center; gap:8px; box-shadow:0 1px 4px rgba(0,0,0,0.04);">
                        <i class="fas fa-calendar-alt" style="color:#3b82f6; font-size:13px;"></i>
                        <span style="font-size:13px; font-weight:700; color:#374151;">{{ now()->format('F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <main class="main-content">
                @if(session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle" style="color:#22c55e; font-size:18px; margin-top:1px;"></i>
                        <span style="font-size:13px; font-weight:600;">{{ session('success') }}</span>
                    </div>
                @endif
                @if($errors->has('cr') || $errors->has('saw'))
                    <div class="alert-error">
                        <i class="fas fa-circle-exclamation" style="color:#ef4444; font-size:18px; margin-top:1px;"></i>
                        <div style="font-size:13px; font-weight:500;">
                            @foreach($errors->all() as $error)<p style="margin:0 0 4px;">{{ $error }}</p>@endforeach
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
            document.body.style.overflow = '';
        }
        document.getElementById('sidebar-overlay').addEventListener('click', closeSidebar);

        // SweetAlert2 delete confirmation
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.form-delete').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-5 py-2 font-bold text-sm',
                            cancelButton: 'rounded-xl px-5 py-2 font-bold text-sm'
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>