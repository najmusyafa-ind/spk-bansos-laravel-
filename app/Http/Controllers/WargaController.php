<?php

namespace App\Http\Controllers;

use App\Helpers\NilaiMapper;
use App\Http\Requests\StoreWargaRequest;
use App\Http\Requests\UpdateWargaRequest;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $query = Warga::where('status_aktif', true);

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $wargas = $query->orderBy('nama')->paginate(15)->withQueryString();
        $periodeAktif = now()->format('Y-m');

        return view('warga.index', compact('wargas', 'periodeAktif'));
    }

    public function show(Warga $warga)
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        $periodeAktif = now()->format('Y-m');

        $penilaians = Penilaian::where('warga_id', $warga->id)
            ->where('periode', $periodeAktif)
            ->with('kriteria')
            ->get()
            ->keyBy('kriteria_id');

        return view('warga.show', compact('warga', 'kriterias', 'penilaians', 'periodeAktif'));
    }

    public function create()
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        return view('warga.create', compact('kriterias'));
    }

    public function store(StoreWargaRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $warga = Warga::create([
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'kelurahan' => $validated['kelurahan'],
            ]);

            $periode = now()->format('Y-m');
            $kriterias = Kriteria::orderBy('urutan')->get()->keyBy('kode');

            $nilais = [
                'C1' => $validated['penghasilan'],
                'C2' => $validated['tanggungan'],
                'C3' => $validated['rumah'],
                'C4' => $validated['pekerjaan'],
                'C5' => $validated['aset'],
            ];

            foreach ($nilais as $kode => $rawValue) {
                if (!isset($kriterias[$kode]))
                    continue;

                $nilaiNum = NilaiMapper::map($kode, $rawValue) ?? 1;

                Penilaian::create([
                    'warga_id' => $warga->id,
                    'kriteria_id' => $kriterias[$kode]->id,
                    'nilai_raw' => $rawValue,
                    'nilai_numerik' => $nilaiNum,
                    'periode' => $periode,
                ]);
            }

            session(['_last_warga_nama' => $warga->nama]);
        });

        return redirect()->route('warga.index')
            ->with('success', 'Data warga ' . $validated['nama'] . ' berhasil ditambahkan!');
    }

    public function edit(Warga $warga)
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        $periodeAktif = now()->format('Y-m');

        $penilaians = Penilaian::where('warga_id', $warga->id)
            ->where('periode', $periodeAktif)
            ->get()
            ->keyBy('kriteria_id');

        return view('warga.edit', compact('warga', 'kriterias', 'penilaians', 'periodeAktif'));
    }

    public function update(UpdateWargaRequest $request, Warga $warga)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $warga) {
            $warga->update([
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'],
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'kelurahan' => $validated['kelurahan'],
            ]);

            $periode = now()->format('Y-m');
            $kriterias = Kriteria::orderBy('urutan')->get()->keyBy('kode');

            $nilais = [
                'C1' => $validated['penghasilan'],
                'C2' => $validated['tanggungan'],
                'C3' => $validated['rumah'],
                'C4' => $validated['pekerjaan'],
                'C5' => $validated['aset'],
            ];

            foreach ($nilais as $kode => $rawValue) {
                if (!isset($kriterias[$kode]))
                    continue;

                $nilaiNum = NilaiMapper::map($kode, $rawValue) ?? 1;

                Penilaian::updateOrCreate(
                    [
                        'warga_id' => $warga->id,
                        'kriteria_id' => $kriterias[$kode]->id,
                        'periode' => $periode,
                    ],
                    [
                        'nilai_raw' => $rawValue,
                        'nilai_numerik' => $nilaiNum,
                    ]
                );
            }
        });

        return redirect()->route('warga.index')
            ->with('success', 'Data warga ' . $validated['nama'] . ' berhasil diperbarui!');
    }

    public function destroy(Warga $warga)
    {
        $nama = $warga->nama;
        $warga->delete();

        return redirect()->route('warga.index')
            ->with('success', 'Data warga ' . $nama . ' berhasil dihapus dari sistem!');
    }
}
