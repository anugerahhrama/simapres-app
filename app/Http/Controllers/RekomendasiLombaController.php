<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\RekomendasiLomba;
use App\Models\TingkatanLomba;
use App\Models\User;
use Illuminate\Http\Request;

class RekomendasiLombaController extends Controller
{
    private function hitungSpk(User $user, Lomba $lomba): float
    {
        // 1. C1: Minat
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
        $pref = $user->preferensiTingkat;
        $c4 = 1;
        if ($pref) {
            if ($lomba->tingkatan_lomba_id == $pref->pilihan_utama_id) $c4 = 5;
            elseif ($lomba->tingkatan_lomba_id == $pref->pilihan_kedua_id) $c4 = 3;
            elseif ($lomba->tingkatan_lomba_id == $pref->pilihan_ketiga_id) $c4 = 1;
        } else {
            $c4 = $lomba->tingkatanLomba->skor_default ?? 1;
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

        // 6. Hitung skor akhir (bobot bisa diambil dari config/bobot_kriterias table)
        return $c1 * 0.25 + $c2 * 0.10 + $c3 * 0.10 + $c4 * 0.20 + $c5 * 0.35;
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

        // // 1. Ambil semua lomba yang sudah diverifikasi
        // $lombas = Lomba::with(['tingkatanLomba', 'minat', 'keahlian'])
        //     ->where('status_verifikasi', 'verified')
        //     ->get();

        // $rekomendasi = [];

        // // 2. Hitung skor SPK per lomba
        // foreach ($lombas as $lomba) {
        //     $skor = $this->hitungSpk($user, $lomba);

        //     $rekomendasi[] = (object)[
        //         'lomba' => $lomba,
        //         'skor' => $skor
        //     ];
        // }

        // // 3. Urutkan berdasarkan skor tertinggi
        // usort($rekomendasi, fn($a, $b) => $b->skor <=> $a->skor);

        return view('lombaMhs.index', compact('breadcrumb', 'tingkatanLomba'));
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
    public function show(RekomendasiLomba $rekomendasiLomba)
    {
        //
    }

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
