<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\SpkBobot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SpkController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Perhitungan SPK',
            'list'  => ['Home', 'SPK']
        ];

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
            $column = array_column($data, $c);
            $maxValues[$c] = !empty($column) ? max($column) : 1; // atau 0, sesuai kebutuhan
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
            'breadcrumb' => $breadcrumb,
            'bobot' => SpkBobot::first(),
        ]);
    }

    private function getRawCValues(User $user, Lomba $lomba): array
    {
        // Gunakan relasi yang benar
        $userKeahlianIds = $user->keahlian->pluck('id')->toArray();
        $lombaKeahlianIds = $lomba->keahlian->pluck('id')->toArray();
        $c1 = count(array_intersect($userKeahlianIds, $lombaKeahlianIds)) > 0 ? 3 : 1;

        $c2 = $lomba->jenis_pendaftaran === 'tim' ? 3 : 1;

        $harga = $lomba->harga_pendaftaran ?? 0;
        $c3 = $harga == 0 ? 3 : 1;

        $c4 = 1;
        if ($pref = $user->preferensiTingkatLomba) {
            // logika sesuai kebutuhan
        }

        // Pastikan nama kolom benar
        $hadiah = $lomba->perkiraan_hadiah ?? '';

        if (is_string($hadiah) && str_starts_with($hadiah, '[')) {
            $hadiah = json_decode($hadiah, true);
        } elseif (is_string($hadiah)) {
            $hadiah = [$hadiah];
        }
        $hadiah = is_array($hadiah) ? $hadiah : [];
        $uang = in_array('uang', $hadiah);
        $trofi = in_array('trofi', $hadiah);
        $sertifikat = in_array('sertifikat', $hadiah);
        $c5 = match (true) {
            $uang && $trofi && $sertifikat => 3,
            $uang && $sertifikat => 2,
            default => 1,
        };

        $getBobot = SpkBobot::where('user_id', $user->id)->first();

        $bobot = [
            'c1' => $getBobot->c1 ?? 0,
            'c2' => $getBobot->c2 ?? 0,
            'c3' => $getBobot->c3 ?? 0,
            'c4' => $getBobot->c4 ?? 0,
            'c5' => $getBobot->c5 ?? 0,
        ];

        return compact('c1', 'c2', 'c3', 'c4', 'c5', 'bobot');
    }

    public function edit(string $id)
    {
        $bobot = SpkBobot::where('user_id', $id)->first();

        return view('spk.edit', compact('bobot'));
    }

    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    public function update(Request $request, $id)
    {
        return $this->storeOrUpdate($request, $id);
    }



    public function storeOrUpdate(Request $request, $id = null)
    {
        $total = $request->c1 + $request->c2 + $request->c3 + $request->c4 + $request->c5;

        if (round($total, 3) !== 1.0) {
            return response()->json([
                'status' => false,
                'message' => 'Total bobot harus berjumlah 1. Silakan sesuaikan kembali.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'c1' => 'required|numeric|min:0',
            'c2' => 'required|numeric|min:0',
            'c3' => 'required|numeric|min:0',
            'c4' => 'required|numeric|min:0',
            'c5' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal. Silakan periksa kembali input Anda.',
                'msgField' => $validator->errors(),
            ]);
        }

        $data = [
            'user_id' => Auth::user()->id,
            'c1' => $request->c1,
            'c2' => $request->c2,
            'c3' => $request->c3,
            'c4' => $request->c4,
            'c5' => $request->c5,
        ];

        if ($id) {
            $bobot = SpkBobot::find($id);
            if (!$bobot) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan.',
                ]);
            }
            $bobot->update($data);
            $message = 'Bobot SPK berhasil diperbarui.';
        } else {
            SpkBobot::create($data);
            $message = 'Bobot SPK berhasil ditambahkan.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
