<?php

namespace App\Http\Controllers;

use App\Models\Keahlian;
use App\Models\ProgramStudi;
use App\Models\SpkBobot;
use App\Models\TingkatanLomba;
use App\Models\User;
use App\Models\UserKeahlian;
use App\Models\UserTingkatLomba;
use App\Models\Jenis;
use App\Models\Biaya;
use App\Models\Hadiah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)
            ->with(['jenis', 'biaya', 'hadiah'])
            ->first();

        $bobot = SpkBobot::where('user_id', $user->id)->first();
        $tingkatanLomba = TingkatanLomba::all();

        // Ambil preferensi tingkatan lomba jadi array untuk ditampilkan
        $preferensi = $user->preferensiTingkatLomba;
        $tingkatanDipilih = collect();

        if ($preferensi) {
            if ($preferensi->pilihan_utama_id) {
                $tingkatanDipilih->push(TingkatanLomba::find($preferensi->pilihan_utama_id));
            }
            if ($preferensi->pilihan_kedua_id) {
                $tingkatanDipilih->push(TingkatanLomba::find($preferensi->pilihan_kedua_id));
            }
            if ($preferensi->pilihan_ketiga_id) {
                $tingkatanDipilih->push(TingkatanLomba::find($preferensi->pilihan_ketiga_id));
            }
        }

        return view('profile.index', [
            'user' => $user,
            'breadcrumb' => (object) [
                'title' => 'Profile User',
                'list'  => ['Home', 'Profile']
            ],
            'bobot' => $bobot,
            'tingkatanLomba' => $tingkatanLomba,
            'tingkatanDipilih' => $tingkatanDipilih,
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('id', $id)->with('detailUser')->first();

        $prodi = ProgramStudi::all();

        return view('profile.edit', compact('user', 'prodi'));
    }

    public function editPhoto(string $id)
    {
        $user = User::where('id', $id)->with('detailUser')->first();

        return view('profile.photo', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_induk' => 'required|string|max:20',
            'phone' => 'required|string|min:8|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|exists:program_studis,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $user = User::findOrFail($id);
        $user->email = $request->email;
        $user->save();

        $detail = $user->detailUser;
        $detail->name = $request->name;
        $detail->no_induk = $request->no_induk;
        $detail->phone = $request->phone;
        $detail->jenis_kelamin = $request->jenis_kelamin;
        $detail->prodi_id = $request->prodi;
        $detail->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui.'
        ]);
    }

    public function updatePhoto(Request $request, string $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // max 2MB
        ]);

        $user = User::findOrFail($id);
        $detail = $user->detailUser;

        if ($detail->photo_file && Storage::exists('public/' . $detail->photo_file)) {
            Storage::delete('public/' . $detail->photo_file);
        }

        $photoPath = $request->file('photo')->store('uploads/profile', 'public');

        // Update path di database
        $detail->photo_file = $photoPath;
        $detail->save();

        return response()->json([
            'status' => true,
            'message' => 'Foto berhasil diperbarui.',
            'photo_url' => asset('storage/' . $photoPath)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function createKeahlian()
    {
        $allKeahlian = Keahlian::all();

        return view('profile.keahlian.create', compact('allKeahlian'));
    }

    public function storeKeahlian(Request $request)
    {
        $user = Auth::user();
        $inputKeahlian = $request->input('keahlian'); // mix of IDs and new names
        $keahlianIds = [];

        foreach ($inputKeahlian as $item) {
            if (is_numeric($item)) {
                $keahlianIds[] = (int) $item;
            } else {
                $existing = Keahlian::whereRaw('LOWER(nama_keahlian) = ?', [strtolower($item)])->first();

                if ($existing) {
                    $keahlianIds[] = $existing->id;
                } else {
                    $new = Keahlian::create(['nama_keahlian' => $item]);
                    $keahlianIds[] = $new->id;
                }
            }
        }

        foreach ($keahlianIds as $id) {
            UserKeahlian::firstOrCreate([
                'user_id' => $user->id,
                'keahlian_id' => $id
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Keahlian berhasil disimpan.'
        ]);
    }

    public function deleteKeahlian($id)
    {
        $user = Auth::user();

        $keahlianUser = UserKeahlian::where('id', $id)->where('user_id', $user->id)->first();

        if (!$keahlianUser) {
            return response()->json([
                'status' => false,
                'message' => 'Data keahlian tidak ditemukan atau bukan milik Anda.'
            ]);
        }

        $keahlianUser->delete();

        return response()->json([
            'status' => true,
            'message' => 'Keahlian berhasil dihapus.'
        ]);
    }

    public function tingkatanCreate()
    {
        $user = Auth::user();
        $tingkatan = TingkatanLomba::all();
        $preferensi = UserTingkatLomba::where('user_id', $user->id)->first();

        $preferensiIds = [];

        if ($preferensi) {
            $preferensiIds = array_filter([
                $preferensi->pilihan_utama_id,
                $preferensi->pilihan_kedua_id,
                $preferensi->pilihan_ketiga_id,
            ]);
        }

        $orderedTingkatan = $tingkatan->sortBy(function ($item) use ($preferensiIds) {
            $index = array_search($item->id, $preferensiIds);
            return $index !== false ? $index : 999;
        });


        return view('profile.tingkatan.create', [
            'tingkatan' => $orderedTingkatan,
            'preferensi' => $preferensi,
            'user' => $user,
        ]);
    }

    public function tingkatanStore(Request $request)
    {
        $user = Auth::user();

        $urutan = $request->input('urutan');

        UserTingkatLomba::where('user_id', $user->id)->delete();

        UserTingkatLomba::create([
            'user_id' => $user->id,
            'pilihan_utama_id' => $urutan[0] ?? null,
            'pilihan_kedua_id' => $urutan[1] ?? null,
            'pilihan_ketiga_id' => $urutan[2] ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Preferensi berhasil disimpan!'
        ]);
    }

    // --- JENIS PENDAFTARAN ---
    public function createJenis()
    {
        return view('profile.jenis.create');
    }

    public function storeJenis(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'jenis_pendaftaran' => 'required|in:individu,tim'
        ]);

        Jenis::updateOrCreate(
            ['user_id' => $user->id],
            ['jenis_pendaftaran' => $request->jenis_pendaftaran]
        );

        return response()->json([
            'status' => true,
            'message' => 'Preferensi jenis pendaftaran berhasil disimpan.'
        ]);
    }

    public function deleteJenis($id)
    {
        $user = Auth::user();
        $data = Jenis::where('id', $id)->where('user_id', $user->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ]);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Preferensi jenis pendaftaran berhasil dihapus.'
        ]);
    }

    // --- BIAYA PENDAFTARAN ---
    public function createBiaya()
    {
        return view('profile.biaya.create');
    }

    public function storeBiaya(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'biaya' => 'required|in:gratis,berbayar'
        ]);

        Biaya::updateOrCreate(
            ['user_id' => $user->id],
            ['biaya' => $request->biaya]
        );

        return response()->json([
            'status' => true,
            'message' => 'Preferensi biaya pendaftaran berhasil disimpan.'
        ]);
    }

    public function deleteBiaya($id)
    {
        $user = Auth::user();
        $data = Biaya::where('id', $id)->where('user_id', $user->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ]);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Preferensi biaya pendaftaran berhasil dihapus.'
        ]);
    }

    // --- HADIAH/BENEFIT ---
    public function createHadiah()
    {
        return view('profile.hadiah.create');
    }

    public function storeHadiah(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'hadiah' => 'required|in:uang,sertifikat,trofi,benefit'
        ]);

        Hadiah::updateOrCreate(
            ['user_id' => $user->id],
            ['hadiah' => $request->hadiah]
        );

        return response()->json([
            'status' => true,
            'message' => 'Preferensi hadiah/benefit berhasil disimpan.'
        ]);
    }

    public function deleteHadiah($id)
    {
        $user = Auth::user();
        $data = Hadiah::where('id', $id)->where('user_id', $user->id)->first();
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan atau bukan milik Anda.'
            ]);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Preferensi hadiah/benefit berhasil dihapus.'
        ]);
    }
}
