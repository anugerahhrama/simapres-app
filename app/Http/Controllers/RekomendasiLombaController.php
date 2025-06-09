<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\RekomendasiLomba;
use App\Models\TingkatanLomba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiLombaController extends Controller
{
    private function hitungSpk(User $user, Lomba $lomba): float
    {
        // 1. C1: Keahlian
        $c1 = $user->keahlian->contains('id', $lomba->bidang_keahlian_id) ? 5 : 1;

        // 2. C2: Jenis Pendaftaran
        $c2 = $lomba->jenis_pendaftaran == 'gratis' ? 5 : 1;

        // 3. C3: Biaya Pendaftaran
        $harga = $lomba->harga_pendaftaran ?? 0;
        $c3 = match (true) {
            $harga <= 50000 => 5,
            $harga <= 150000 => 3,
            $harga <= 300000 => 2,
            $harga > 300000 => 1,
            default => 1
        };

        // 4. C4: Tingkat Lomba vs preferensi user
        $preferensi = $user->preferensiTingkatLomba;

        $c4 = 1;
        if ($preferensi) {
            if ($lomba->tingkatan_lomba_id == $preferensi->pilihan_utama_id) {
                $c4 = 5;
            } elseif ($lomba->tingkatan_lomba_id == $preferensi->pilihan_kedua_id) {
                $c4 = 3;
            } elseif ($lomba->tingkatan_lomba_id == $preferensi->pilihan_ketiga_id) {
                $c4 = 1;
            }
        }

        // 5. C5: Nilai Benefit
        $hadiah = $lomba->perkiraan_hadiah ?? 0;
        $benefit = $lomba->mendapatkan_sertifikat;
        $c5 = match (true) {
            $hadiah > 5000000 && $benefit => 5,
            $hadiah > 1000000 && $benefit => 4,
            $hadiah >= 500000 || $benefit => 3,
            $hadiah <= 500000 || $benefit => 2,
            default => 1
        };

        $bobot = [
            'c1' => 0.25,
            'c2' => 0.10,
            'c3' => 0.10,
            'c4' => 0.20,
            'c5' => 0.35,
        ];

        // 6. Hitung skor akhir (bobot bisa diambil dari config/bobot_kriterias table)
        return $c1 * $bobot['c1'] + $c2 * $bobot['c2'] + $c3 * $bobot['c3'] + $c4 * $bobot['c4'] + $c5 * $bobot['c5'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $breadcrumb = (object) [
            'title' => 'Rekomendasi Lomba',
            'list'  => ['Home', 'Lomba']
        ];

        $tingkatanLomba = TingkatanLomba::all();

        // 1. Ambil semua lomba yang sudah diverifikasi
        $lombas = Lomba::with(['tingkatanLomba', 'keahlian'])
            ->where('status_verifikasi', 'verified')
            ->get();

        $rekomendasi = [];

        // 2. Hitung skor SPK per lomba
        foreach ($lombas as $lomba) {
            $skor = $this->hitungSpk($user, $lomba);

            $rekomendasi[] = (object)[
                'lomba' => $lomba,
                'skor' => $skor
            ];
        }

        // 3. Urutkan berdasarkan skor tertinggi
        usort($rekomendasi, fn($a, $b) => $b->skor <=> $a->skor);

        return view('lombaMhs.index', compact('breadcrumb', 'tingkatanLomba', 'rekomendasi'));
    }

    public function list(Request $request)
    {
        $user = Auth::user();

        $lombas = Lomba::with(['tingkatanLomba', 'keahlian',])->where('status_verifikasi', 'verified');

        // Filter
        if ($request->has('tingkatan_filter')) {
            $lombas->where('tingkatan_lomba_id', $request->tingkatan_filter);
        }

        if ($request->has('search')) {
            $lombas->where('judul', 'like', '%' . $request->search . '%');
        }

        $lombas = $lombas->get();

        // Hitung skor SPK
        $rekomendasi = $lombas->map(function ($lomba) use ($user) {
            $skor = $this->hitungSpk($user, $lomba);
            $lomba->skor = $skor;
            return $lomba;
        })->sortByDesc('skor')->values();

        dd($rekomendasi);

        // Paginate manual (karena kita pakai collection)
        $perPage = $request->input('per_page', 12);
        $page = $request->input('page', 1);
        $paginated = $rekomendasi->forPage($page, $perPage);
        $meta = [
            'current_page' => $page,
            'last_page' => ceil($rekomendasi->count() / $perPage),
            'from' => ($page - 1) * $perPage + 1,
            'to' => ($page - 1) * $perPage + count($paginated),
            'total' => $rekomendasi->count(),
        ];

        return response()->json([
            'data' => $paginated->values(),
            'meta' => $meta
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RekomendasiLomba $rekomendasiLomba) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekomendasiLomba $rekomendasiLomba)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RekomendasiLomba $rekomendasiLomba)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekomendasiLomba $rekomendasiLomba)
    {
        //
    }
}
