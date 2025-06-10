<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user->load('keahlian', 'preferensiTingkatLomba');

        $lombas = Lomba::with(['keahlian', 'tingkatanLomba'])->where('status_verifikasi', 'verified')->get();

        // Step 1: Ambil semua nilai C1–C5
        $data = [];
        foreach ($lombas as $lomba) {
            $calc = $this->getRawCValues($user, $lomba);
            $data[] = [
                'lomba' => $lomba,
                'nilai' => $calc
            ];
        }

        // Step 2: Normalisasi tiap kriteria
        $maxValues = [];
        foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
            $maxValues[$c] = max(array_column(array_column($data, 'nilai'), $c));
        }

        // Step 3: Hitung Normalisasi, Perkalian Bobot, WSM, WPM, WASPAS
        $result = [];
        foreach ($data as $row) {
            $norm = [];
            $bobot = $row['nilai']['bobot'];

            foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
                $norm[$c] = $row['nilai'][$c] / max($maxValues[$c], 1); // normalisasi
            }

            // Hitung WSM & WPM
            $wsm = 0;
            $wpm = 1;
            foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
                $wsm += $norm[$c] * $bobot[$c];
                $wpm *= pow($norm[$c], $bobot[$c]);
            }

            $waspas = 0.5 * $wsm + 0.5 * $wpm;

            $result[] = [
                'lomba' => $row['lomba'],
                'nilai' => $row['nilai'],
                'normalisasi' => $norm,
                'wsm' => $wsm,
                'wpm' => $wpm,
                'waspas' => $waspas,
            ];
        }

        // Step 4: Ranking
        usort($result, fn($a, $b) => $b['waspas'] <=> $a['waspas']);
        foreach ($result as $i => &$row) {
            $row['ranking'] = $i + 1;
        }

        return view('spk.index', [
            'hasil' => $result,
        ]);
    }

    private function getRawCValues(User $user, Lomba $lomba): array
    {
        $userKeahlianIds = $user->keahlian->pluck('id')->toArray();
        $lombaKeahlianIds = $lomba->keahlian->pluck('id')->toArray();
        $c1 = count(array_intersect($userKeahlianIds, $lombaKeahlianIds)) > 0 ? 3 : 1;

        $c2 = $lomba->jenis_pendaftaran === 'tim' ? 3 : 1;

        $harga = $lomba->harga_pendaftaran ?? 0;
        $c3 = $harga == 0 ? 3 : 1;

        $c4 = 1;
        if ($pref = $user->preferensiTingkatLomba) {
            $c4 = match ($lomba->tingkatan_lomba_id) {
                $pref->pilihan_utama_id => 5,
                $pref->pilihan_kedua_id => 3,
                $pref->pilihan_ketiga_id => 1,
                default => 1,
            };
        }

        $hadiah = $lomba->hadiah ?? [];
        $uang = in_array('uang', $hadiah);
        $trofi = in_array('trofi', $hadiah);
        $sertifikat = in_array('sertifikat', $hadiah);
        $c5 = match (true) {
            $uang && $trofi && $sertifikat => 5,
            $uang && $sertifikat && !$trofi => 4,
            $trofi && $sertifikat && !$uang => 3,
            ($uang xor $trofi) && !$sertifikat => 2,
            !$uang && !$trofi && $sertifikat => 1,
            default => 1,
        };

        $bobot = [
            'c1' => 0.4,
            'c2' => 0.2,
            'c3' => 0.1,
            'c4' => 0.2,
            'c5' => 0.1,
        ];

        return compact('c1', 'c2', 'c3', 'c4', 'c5', 'bobot');
    }
}
