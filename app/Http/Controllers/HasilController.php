<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Kriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $periodeAktif = now()->format('Y-m');
        $periode      = $request->input('periode', $periodeAktif);
        $status       = $request->input('status');

        $periodes = Hasil::select('periode')->distinct()->orderByDesc('periode')->pluck('periode');

        $query = Hasil::with('warga')->where('periode', $periode)->orderBy('ranking');
        if ($status) {
            $query->where('status', $status);
        }

        $hasils    = $query->paginate(20)->withQueryString();
        $kriterias = Kriteria::orderBy('urutan')->get();

        $stats = [
            'prioritas'   => Hasil::where('periode', $periode)->where('status', 'prioritas')->count(),
            'layak'       => Hasil::where('periode', $periode)->where('status', 'layak')->count(),
            'tidak_layak' => Hasil::where('periode', $periode)->where('status', 'tidak_layak')->count(),
        ];

        return view('hasil.index', compact('hasils', 'periode', 'periodeAktif', 'periodes', 'kriterias', 'stats', 'status'));
    }

    public function exportPdf(Request $request)
    {
        $periode   = $request->input('periode', now()->format('Y-m'));
        $kriterias = Kriteria::orderBy('urutan')->get();

        $hasils = Hasil::with('warga')
            ->where('periode', $periode)
            ->orderBy('ranking')
            ->get();

        $stats = [
            'prioritas'   => $hasils->where('status', 'prioritas')->count(),
            'layak'       => $hasils->where('status', 'layak')->count(),
            'tidak_layak' => $hasils->where('status', 'tidak_layak')->count(),
        ];

        $pdf = Pdf::loadView('hasil.pdf', compact('hasils', 'periode', 'kriterias', 'stats'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        return $pdf->download("ranking-bansos-{$periode}.pdf");
    }
}
