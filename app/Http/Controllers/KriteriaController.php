<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::orderBy('urutan')->get();
        return view('kriteria.index', compact('kriterias'));
    }

    public function create()
    {
        return view('kriteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'      => 'required|string|max:10|unique:kriterias,kode',
            'nama'      => 'required|string|max:100',
            'tipe'      => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:1',
        ]);

        Kriteria::create($request->only('kode', 'nama', 'tipe', 'deskripsi', 'urutan'));

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria ' . $request->kode . ' berhasil ditambahkan!');
    }

    public function edit(Kriteria $kriteria)
    {
        return view('kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'tipe'      => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string',
            'urutan'    => 'required|integer|min:1',
        ]);

        // Jika tipe berubah, reset bobot karena AHP harus dihitung ulang
        $resetBobot = $kriteria->tipe !== $request->tipe;

        $kriteria->update(array_merge(
            $request->only('nama', 'tipe', 'deskripsi', 'urutan'),
            $resetBobot ? ['bobot' => null] : []
        ));

        $msg = 'Kriteria ' . $kriteria->kode . ' berhasil diperbarui!';
        if ($resetBobot) {
            $msg .= ' ⚠️ Tipe berubah — bobot AHP di-reset. Harap hitung ulang matriks AHP.';
        }

        return redirect()->route('kriteria.index')->with('success', $msg);
    }

    public function destroy(Kriteria $kriteria)
    {
        // Cek apakah ada penilaian yang menggunakan kriteria ini
        if ($kriteria->penilaians()->exists()) {
            return back()->withErrors([
                'delete' => 'Kriteria ' . $kriteria->kode . ' tidak bisa dihapus karena sudah memiliki data penilaian.'
            ]);
        }

        $kode = $kriteria->kode;
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria ' . $kode . ' berhasil dihapus.');
    }

    /**
     * Reset semua bobot kriteria (agar admin tahu harus AHP ulang)
     */
    public function resetBobot()
    {
        Kriteria::query()->update(['bobot' => null]);
        \App\Models\AhpComparison::truncate();

        return redirect()->route('ahp.index')
            ->with('success', 'Semua bobot AHP berhasil di-reset. Silakan isi ulang matriks perbandingan.');
    }
}
