@extends('layouts.admin')
@section('title', 'Detail Warga')
@section('page_title', $warga->nama)
@section('page_subtitle', 'Alamat: ' . $warga->alamat)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Informasi Warga</h3>
        <dl class="space-y-3">
            @foreach([['Nama', $warga->nama], ['Alamat', $warga->alamat ?? '-'], ['RT/RW', 'RT '.$warga->rt.' / RW '.$warga->rw], ['Kelurahan', $warga->kelurahan]] as [$label, $value])
            <div>
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $label }}</dt>
                <dd class="text-sm font-medium text-gray-800 mt-0.5">{{ $value }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Penilaian Kriteria — Periode {{ $periodeAktif }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kriteria</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nilai Teks</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Nilai Numerik</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($kriterias as $k)
                    @php $p = $penilaians[$k->id] ?? null; @endphp
                    <tr class="table-row-hover">
                        <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $k->kode }} — {{ $k->nama }}</td>
                        <td class="px-6 py-3.5"><span class="text-xs font-bold px-2 py-1 rounded-lg {{ $k->tipe === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($k->tipe) }}</span></td>
                        <td class="px-6 py-3.5 text-gray-600">{{ $p?->nilai_raw ?? '—' }}</td>
                        <td class="px-6 py-3.5 text-center font-bold text-blue-700">{{ $p?->nilai_numerik ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-4">
    <a href="{{ route('warga.index') }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-arrow-left mr-1"></i>Kembali ke daftar warga</a>
</div>
@endsection
