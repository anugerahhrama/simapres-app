<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\DetailUser;
use App\Models\User;
use App\Models\TingkatanLomba;
use App\Models\Keahlian;
use App\Models\Minat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $totalLombas = Lomba::where('status_verifikasi', 'verified')->count();
        $totalLombaPending = Lomba::where('status_verifikasi', 'pending')->count();
        $totalLombaAktif = Lomba::where('status_verifikasi', 'verified')
            ->where('awal_registrasi', '<=', now())
            ->where('akhir_registrasi', '>=', now())
            ->count();
        $totalLombaSelesai = Lomba::where('status_verifikasi', 'verified')
            ->where('akhir_registrasi', '<', now())
            ->count();

        return view('lomba.index', compact('breadcrumb', 'tingkatanLomba', 'totalLombas', 'totalLombaPending', 'totalLombaAktif', 'totalLombaSelesai'));
    }

    public function list(Request $request)
    {
        $data = Lomba::with(['tingkatanLomba'])
            ->when($request->tingkatan, function ($query) use ($request) {
                $query->whereHas('tingkatanLomba', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->tingkatan . '%');
                });
            })
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori', $request->kategori);
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
            ->addColumn('status', function ($row) {
                if ($row->status_verifikasi === 'verified') {
                    return '<span class="badge badge-success" style="font-size: 12px;">Terverifikasi</span>';
                } else if ($row->status_verifikasi === 'rejected') {
                    return '<span class="badge badge-danger" style="font-size: 12px;">Ditolak</span>';
                } else {
                    return '<span class="badge badge-secondary" style="font-size: 12px;">Belum Terverifikasi</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<div class="d-flex justify-content-center align-items-center" style="gap: 2px;">';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.show', $row->id) . '\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<a href="' . route('lomba.edit', $row->id) . '" class="btn btn-warning btn-sm" style="text-decoration: none;">Edit</a>';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.confirm', $row->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
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
            'title' => 'Tambah Lomba Baru',
            'list'  => ['Home', 'Lomba', 'Create']
        ];

        return view('lomba.create', compact('tingkatanLombas', 'keahlians', 'minats', 'breadcrumb', 'activeMenu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('jadwal_registrasi')) {
            [$awal, $akhir] = explode(' - ', $request->jadwal_registrasi);
            $request->merge([
                'awal_registrasi' => trim($awal),
                'akhir_registrasi' => trim($akhir),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non akademik',
            'tingkatan_lomba_id' => 'required|exists:tingkatan_lombas,id',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'link_registrasi' => 'required|url',
            'awal_registrasi' => 'required|date',
            'akhir_registrasi' => 'required|date|after_or_equal:awal_registrasi',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'string|max:255',
            'jenis_pendaftaran' => 'required|in:individu,tim',
            'jenis_biaya' => 'required|in:gratis,berbayar',
            'harga_pendaftaran' => 'nullable|numeric|min:0',
            'hadiah' => 'required|array|min:1',
            'hadiah.*' => 'in:uang,trofi,sertifikat',
            'status_verifikasi' => 'required|in:pending,verified,rejected',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal saat membuat lomba', [
                'errors' => $validator->errors()->all(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $validated = $validator->validated();

            if ($validated['jenis_biaya'] === 'gratis') {
                $validated['harga_pendaftaran'] = 0;
                Log::info('Jenis biaya gratis, harga di-set ke 0');
            }

            $lomba = Lomba::create([
                'judul' => $validated['judul'],
                'kategori' => $validated['kategori'],
                'tingkatan_lomba_id' => $validated['tingkatan_lomba_id'],
                'penyelenggara' => $validated['penyelenggara'],
                'deskripsi' => $validated['deskripsi'],
                'link_registrasi' => $validated['link_registrasi'],
                'awal_registrasi' => $validated['awal_registrasi'],
                'akhir_registrasi' => $validated['akhir_registrasi'],
                'jenis_pendaftaran' => $validated['jenis_pendaftaran'],
                'harga_pendaftaran' => $validated['harga_pendaftaran'],
                'hadiah' => $validated['hadiah'],
                'status_verifikasi' => $validated['status_verifikasi'],
                'created_by' => auth()->id(),
            ]);

            Log::info('Lomba berhasil disimpan.', ['lomba_id' => $lomba->id]);

            // Proses keahlian
            $keahlianIDs = [];
            foreach ($validated['keahlian'] as $input) {
                if (is_numeric($input)) {
                    $keahlianIDs[] = (int) $input;
                } else {
                    $keahlian = Keahlian::firstOrCreate(
                        ['nama_keahlian' => trim($input)]
                    );
                    $keahlianIDs[] = $keahlian->id;

                    Log::info('Keahlian baru dibuat:', ['nama' => $input, 'id' => $keahlian->id]);
                }
            }

            $lomba->keahlian()->sync($keahlianIDs);

            DB::commit();

            return redirect()->route('lomba.index')->with('success', 'Lomba berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Terjadi error saat menyimpan lomba', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
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
        $breadcrumb = (object) [
            'title' => 'Edit Lomba',
            'list'  => ['Home', 'Lomba', 'Edit']
        ];

        $lomba = Lomba::with('keahlian')->findOrFail($lomba->id);
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();

        return view('lomba.edit', compact('lomba', 'tingkatanLombas', 'keahlians', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lomba $lomba)
    {
        if ($request->filled('jadwal_registrasi')) {
            [$awal, $akhir] = explode(' - ', $request->jadwal_registrasi);
            $request->merge([
                'awal_registrasi' => trim($awal),
                'akhir_registrasi' => trim($akhir),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Akademik,Non akademik',
            'tingkatan_lomba_id' => 'required|exists:tingkatan_lombas,id',
            'penyelenggara' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'link_registrasi' => 'required|url',
            'awal_registrasi' => 'required|date',
            'akhir_registrasi' => 'required|date|after_or_equal:awal_registrasi',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'string|max:255',
            'jenis_pendaftaran' => 'required|in:individu,tim',
            'jenis_biaya' => 'required|in:gratis,berbayar',
            'harga_pendaftaran' => 'nullable|numeric|min:0',
            'hadiah' => 'required|array|min:1',
            'hadiah.*' => 'in:uang,trofi,sertifikat',
            'status_verifikasi' => 'required|in:pending,verified,rejected',
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal saat mengupdate lomba', [
                'errors' => $validator->errors()->all(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa form Anda.');
        }

        try {
            DB::beginTransaction();

            $validated = $validator->validated();

            if ($validated['jenis_biaya'] === 'gratis') {
                $validated['harga_pendaftaran'] = 0;
            }

            $lomba->update([
                'judul' => $validated['judul'],
                'kategori' => $validated['kategori'],
                'tingkatan_lomba_id' => $validated['tingkatan_lomba_id'],
                'penyelenggara' => $validated['penyelenggara'],
                'deskripsi' => $validated['deskripsi'],
                'link_registrasi' => $validated['link_registrasi'],
                'awal_registrasi' => $validated['awal_registrasi'],
                'akhir_registrasi' => $validated['akhir_registrasi'],
                'jenis_pendaftaran' => $validated['jenis_pendaftaran'],
                'harga_pendaftaran' => $validated['harga_pendaftaran'],
                'hadiah' => $validated['hadiah'],
                'status_verifikasi' => $validated['status_verifikasi'],
            ]);

            $keahlianIDs = [];
            foreach ($validated['keahlian'] as $input) {
                if (is_numeric($input)) {
                    $keahlianIDs[] = (int) $input;
                } else {
                    $keahlian = Keahlian::firstOrCreate(['nama_keahlian' => trim($input)]);
                    $keahlianIDs[] = $keahlian->id;
                }
            }
            $lomba->keahlian()->sync($keahlianIDs);

            DB::commit();

            return redirect()->route('lomba.index')->with('success', 'Lomba berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal mengupdate lomba', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
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
