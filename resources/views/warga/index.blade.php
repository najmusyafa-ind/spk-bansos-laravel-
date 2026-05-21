@extends('layouts.admin')
@section('title', 'Data Warga')
@section('page_title', 'Data Warga')
@section('page_subtitle', 'Daftar calon penerima bantuan sosial')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-circle-check text-green-500"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter & Action Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('warga.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="text-xs font-semibold text-gray-500 mb-1 block">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block">RT</label>
                <input type="text" name="rt" value="{{ request('rt') }}" placeholder="Mis: 001" maxlength="5"
                    class="w-28 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 mb-1 block">RW</label>
                <input type="text" name="rw" value="{{ request('rw') }}" placeholder="Mis: 002" maxlength="5"
                    class="w-28 px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white">
            </div>
            <button type="submit"
                class="btn-hover px-5 py-2.5 bg-[#1e2d7a] text-white text-sm font-semibold rounded-xl hover:bg-[#172264] transition-all shadow-md shadow-blue-900/20">
                <i class="fas fa-search mr-1.5"></i> Filter
            </button>
            <a href="{{ route('warga.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-200 transition-all">
                Reset
            </a>
            <a href="{{ route('warga.create') }}"
                class="btn-hover px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-all shadow-md shadow-red-500/20 ml-auto">
                <i class="fas fa-plus mr-1.5"></i> Tambah Warga
            </a>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Daftar Warga</h3>
            <span class="text-xs text-gray-400">Total: {{ $wargas->total() }} warga</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">RT/RW</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kelurahan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Terdaftar
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($wargas as $i => $w)
                        <tr class="table-row-hover">
                            <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $wargas->firstItem() + $i }}</td>
                            <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $w->nama }}</td>
                            <td class="px-6 py-3.5 text-gray-500 text-xs">RT {{ $w->rt }} / RW {{ $w->rw }}</td>
                            <td class="px-6 py-3.5 text-gray-500">{{ $w->kelurahan }}</td>
                            <td class="px-6 py-3.5 text-gray-400 text-xs">{{ $w->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-center flex items-center justify-center gap-1.5">
                                <a href="{{ route('warga.show', $w) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 text-xs font-semibold rounded-lg hover:bg-blue-100 hover:shadow-sm transition-all focus:ring-2 focus:ring-blue-300">
                                    <i class="fas fa-eye text-[10px]"></i> Detail
                                </a>
                                <a href="{{ route('warga.edit', $w) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-100 text-xs font-semibold rounded-lg hover:bg-amber-100 hover:shadow-sm transition-all focus:ring-2 focus:ring-amber-300">
                                    <i class="fas fa-pen text-[10px]"></i> Edit
                                </a>
                                <form action="{{ route('warga.destroy', $w) }}" method="POST" class="inline-block form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 border border-rose-100 text-rose-600 text-xs font-semibold rounded-lg hover:bg-rose-100 hover:shadow-sm transition-all focus:ring-2 focus:ring-rose-300">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                                <i class="fas fa-users mb-3 text-3xl text-gray-200 block"></i>
                                Tidak ada data warga.
                                <a href="{{ route('warga.create') }}" class="text-blue-500 hover:underline ml-1">Tambahkan
                                    sekarang.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($wargas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $wargas->links() }}
            </div>
        @endif
    </div>

@endsection