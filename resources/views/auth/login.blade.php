<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SPK Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-[#1e2d7a] via-[#2b3a8a] to-[#1a237e] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="mb-4 text-center">
            <img src="{{ asset('logo.png') }}" alt="Logo Purbalingga" class="w-16 h-16 mx-auto mb-2 drop-shadow-md">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">SPK Bansos</h1>
            <p class="text-indigo-200 mt-2 text-sm">Sistem Pendukung Keputusan Penerima Bantuan Sosial</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang</h2>
            <p class="text-gray-400 text-sm mb-7">Masuk untuk mengelola data penerima bansos</p>

            @if($errors->any())
                <div
                    class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
                    <i class="fas fa-triangle-exclamation text-red-500"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm transition-all focus:bg-white"
                        placeholder="admin@bansos.id">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm transition-all focus:bg-white"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        Ingat saya
                    </label>
                </div>
                <button type="submit"
                    class="w-full bg-[#1e2d7a] hover:bg-[#172264] text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg shadow-blue-900/30 hover:shadow-xl hover:shadow-blue-900/40 hover:-translate-y-0.5">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center">Default: <code
                        class="bg-gray-100 px-1.5 py-0.5 rounded">admin@bansos.id</code> / <code
                        class="bg-gray-100 px-1.5 py-0.5 rounded">admin123</code></p>
            </div>
        </div>
    </div>

</body>

</html>