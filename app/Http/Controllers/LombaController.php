<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\DetailUser;
use App\Models\User;
use App\Models\TingkatanLomba;
use App\Models\Keahlian;
use App\Models\Minat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LombaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
        'title' => 'Daftar Lomba',
        'list'  => ['Home', 'Lomba']
    ];
    $tingkatanLomba = TingkatanLomba::all();

    return view('lomba.index', compact('breadcrumb', 'tingkatanLomba'));
    }

    public function list(Request $request)
    {
       $data = Lomba::with(['tingkatanLomba']) 
        ->when($request->tingkatan, function ($query) use ($request) {
            $query->whereHas('tingkatanLomba', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->tingkatan . '%');
            });
        });

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judul_lomba', function ($row) {
                return $row->judul;
            })
            ->addColumn('kategori', function ($row) {
                return $row->kategori ?? '-';
            })
            ->addColumn('tingkatan', function ($row) {
                return $row->tingkatanLomba->nama ?? '-';
            })
            ->addColumn('penyelenggara', function ($row) {
                return $row->penyelenggara ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 5px;">';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.show', $row->id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<a href="' . route('lomba.edit', $row->id) . '" class="btn btn-warning btn-sm mr-1" style="text-decoration: none;">Edit</a>';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.confirm', $row->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();
        $minats = Minat::all();

        $breadcrumb = (object) [
            'title' => 'Tambah Lomba',
            'list'  => ['Home', 'Lomba', 'Edit']
        ];

        return view('lomba.create', compact('tingkatanLombas', 'keahlians', 'minats', 'breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'kategori' => 'required',
            'tingkatan_lomba_id' => 'required|exists:tingkatan_lombas,id',
            'penyelenggara' => 'nullable|max:100',
            'deskripsi' => 'nullable|max:255',
            'link_registrasi' => 'nullable|url',
            'awal_registrasi' => 'nullable|date',
            'akhir_registrasi' => 'nullable|date|after_or_equal:awal_registrasi',
            'bidang_keahlian_id' => 'nullable|exists:keahlians,id',
            'minat_id' => 'nullable|exists:minats,id',
            'jenis_pendaftaran' => 'nullable|string|max:100',
            'harga_pendaftaran' => 'nullable|numeric|min:0',
            'perkiraan_hadiah' => 'nullable|string|max:255',
            'mendapatkan_uang' => 'nullable|boolean',
            'mendapatkan_sertifikat' => 'nullable|boolean',
            'nilai_benefit' => 'nullable|numeric|min:0',
            'status_verifikasi' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $lomba = new Lomba();

        $lomba->fill([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tingkatan_lomba_id' => $request->tingkatan_lomba_id,
            'penyelenggara' => $request->penyelenggara,
            'deskripsi' => $request->deskripsi,
            'link_registrasi' => $request->link_registrasi,
            'awal_registrasi' => $request->awal_registrasi,
            'akhir_registrasi' => $request->akhir_registrasi,
            'bidang_keahlian_id' => $request->bidang_keahlian_id,
            'minat_id' => $request->minat_id,
            'jenis_pendaftaran' => $request->jenis_pendaftaran,
            'harga_pendaftaran' => $request->harga_pendaftaran,
            'perkiraan_hadiah' => $request->perkiraan_hadiah,
            'mendapatkan_uang' => $request->mendapatkan_uang ?? 0,
            'mendapatkan_sertifikat' => $request->mendapatkan_sertifikat ?? 0,
            'nilai_benefit' => $request->nilai_benefit,
            'status_verifikasi' => $request->status_verifikasi ?? 'pending',
            'created_by' => auth()->user()->id,
        ]);

        $lomba->save();

        return redirect()->route('lomba.index')->with('success', 'Lomba berhasil ditambahkan!');
    }



    /**
     * Display the specified resource.
     */
    public function show(Lomba $lomba)
    {
        $lomba->load(['tingkatanLomba', 'keahlian', 'minat', 'createdBy']);
        $detailUser = DetailUser::all();
        $users = User::with('detailUser')->get();
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();
        $minats = Minat::all();

        return view('lomba.show', compact('lomba', 'tingkatanLombas', 'keahlians', 'minats', 'detailUser', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lomba $lomba)
    {
        $keahlians = Keahlian::all();
        $minats = Minat::all();

        $breadcrumb = (object) [
            'title' => 'Edit Lomba',
            'list'  => ['Home', 'Lomba', 'Edit']
        ];

        return view('lomba.edit', compact('lomba', 'keahlians', 'minats', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lomba $lomba)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'kategori' => 'required|in:akademik,non akademik',
            'tingkatan' => 'required|in:pemula,lokal,regional,nasional,internasional',
            'penyelenggara' => 'nullable|max:100',
            'deskripsi' => 'nullable|max:255',
            'link_registrasi' => 'nullable|url|max:255',
            'awal_registrasi' => 'nullable|date',
            'akhir_registrasi' => 'nullable|date|after_or_equal:awal_registrasi',
            'bidang_keahlian_id' => 'nullable|exists:keahlians,id',
            'minat_id' => 'nullable|exists:minats,id',
            'jenis_pendaftaran' => 'nullable|string|max:100',
            'harga_pendaftaran' => 'nullable|numeric|min:0',
            'perkiraan_hadiah' => 'nullable|string|max:255',
            'mendapatkan_uang' => 'nullable|boolean',
            'mendapatkan_sertifikat' => 'nullable|boolean',
            'nilai_benefit' => 'nullable|numeric|min:0',
            'status_verifikasi' => 'nullable|in:pending,disetujui,ditolak',
        ]);

        $tingkatanLomba = TingkatanLomba::where('nama', $request->tingkatan)->first();

        $lomba->update([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tingkatan_lomba_id' => $tingkatanLomba ? $tingkatanLomba->id : null,
            'penyelenggara' => $request->penyelenggara,
            'deskripsi' => $request->deskripsi,
            'link_registrasi' => $request->link_registrasi,
            'awal_registrasi' => $request->awal_registrasi,
            'akhir_registrasi' => $request->akhir_registrasi,
            'bidang_keahlian_id' => $request->bidang_keahlian_id,
            'minat_id' => $request->minat_id,
            'jenis_pendaftaran' => $request->jenis_pendaftaran,
            'harga_pendaftaran' => $request->harga_pendaftaran,
            'perkiraan_hadiah' => $request->perkiraan_hadiah,
            'mendapatkan_uang' => $request->mendapatkan_uang ?? 0,
            'mendapatkan_sertifikat' => $request->mendapatkan_sertifikat ?? 0,
            'nilai_benefit' => $request->nilai_benefit,
            'status_verifikasi' => $request->status_verifikasi ?? 'pending',
        ]);

        return redirect()->route('lomba.index')->with('success', 'Lomba berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function confirm(string $id)
    {
        $lomba = Lomba::with('tingkatanLomba')->findOrFail($id);

        return view('lomba.confirm', compact('lomba'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lomba = Lomba::with('tingkatanLomba')->findOrFail($id);

        $deletedData = [
            'judul' => $lomba->judul ?? '-',
            'tingkatan' => $lomba->tingkatanLomba->nama ?? '-',
            'penyelenggara' => $lomba->penyelenggara ?? '-',
            'deskripsi' => $lomba->deskripsi ?? '-',
        ];

        $lomba->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil dihapus.',
            'deleted_data' => $deletedData
        ]);
    }
}
