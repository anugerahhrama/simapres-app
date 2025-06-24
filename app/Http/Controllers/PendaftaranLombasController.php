<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\PendaftaranLombas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PendaftaranLombasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $pendaftar = collect();

        if ($user && $user->level->level_code == 'ADM') {
            // Jika admin, tampilkan hanya yang pending
            $pendaftar = PendaftaranLombas::where('status', 'pending')->get();
        } elseif ($user && $user->level->level_code == 'MHS') {
            // Jika mahasiswa, tampilkan hanya pendaftaran miliknya
            $pendaftar = PendaftaranLombas::where('user_id', $user->id)->get();
        }

        $breadcrumb = (object) [
            'title' => 'Daftar Lomba',
            'list'  => ['Home', 'Lomba']
        ];

        return view('pendaftaranLomba.index', compact('breadcrumb', 'pendaftar'));
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        $query = PendaftaranLombas::query()
            ->with(['user.detailUser.prodi', 'user.bimbingan'])
            ->orderByDesc('id');

        if ($user && $user->level->level_code == 'ADM') {
            $query->where('status', 'pending');
        } elseif ($user && $user->level->level_code == 'MHS') {
            $query->where('user_id', $user->id);
        }

        if ($request->id) {
            $query->where('id', $request->id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function ($pendaftar) {
                return optional(optional($pendaftar->user)->detailUser)->name ?? '-';
            })
            ->addColumn('program_studi', function ($pendaftar) {
                return optional(optional(optional($pendaftar->user)->detailUser)->prodi)->name ?? '-';
            })
            ->addColumn('nama_lomba', function ($pendaftar) {
                return optional($pendaftar->lomba)->judul ?? '-';
            })
            ->addColumn('penyelenggara', function ($pendaftar) {
                return optional($pendaftar->lomba)->penyelenggara ?? '-';
            })
            ->addColumn('dosen_pembimbing', function ($pendaftar) {
                $bimbingan = optional($pendaftar->user)->mahasiswa->first();
                return optional(optional($bimbingan)->dosen)->detailUser->name ?? '-';
            })
            ->addColumn('aksi', function ($pendaftar) {
                $btn = '';

                if (auth()->check()) {
                    $levelCode = auth()->user()->level->level_code;

                    if ($levelCode == 'MHS') {
                        $btn .= '<button onclick="modalAction(\'' . route('pendaftaranLomba.show', $pendaftar->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                    }

                    if ($levelCode == 'ADM') {
                        $btn .= '<button onclick="modalAction(\'' . route('pendaftaranLomba.edit', $pendaftar->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                        $btn .= '<button onclick="modalAction(\'' . route('pendaftaranLomba.confirm', $pendaftar->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                    }
                }

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $slug)
    {
        $lombaId = base64_decode($slug);
        $lomba = Lomba::findOrFail($lombaId);

        $breadcrumb = (object) [
            'title' => 'Pendaftaran lomba ' . $lomba->judul,
            'list'  => ['Home', 'Lomba']
        ];

        return view('lombaMhs.pendaftaran.create', compact('lomba', 'breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lomba = Lomba::findOrFail($request->lomba_id);
        $user = Auth::user()->id;

        try {
            DB::beginTransaction();

            $cek = PendaftaranLombas::where('user_id', $user)->where('lomba_id', $lomba->id)->exists();

            if (!$cek) {
                PendaftaranLombas::create([
                    'user_id' => $user,
                    'lomba_id' => $lomba->id,
                    'tanggal_daftar' => Carbon::now(),
                    'status' => 'pending',
                ]);
                DB::commit();
                $response = Http::head($lomba->link_registrasi); // cepat karena tidak ambil isi

                if ($response->successful()) {
                    return redirect($lomba->link_registrasi);
                } else {
                    return redirect()->back()->with('error', 'Link registrasi tidak tersedia atau tidak valid.');
                }
            } else {
                DB::rollBack();
                return redirect()->back()->withInput();
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $pendaftar = PendaftaranLombas::find($id);

        return view('pendaftaranLomba.show', compact('pendaftar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $pendaftar = PendaftaranLombas::with(['user.detailUser.prodi', 'user.bimbingan'])->find($id);

        // Ambil semua user dengan level_code 'DSN'
        $daftarDosen = User::whereHas('level', function ($q) {
            $q->where('level_code', 'DSN');
        })->with('detailUser')->get();

        return view('pendaftaranLomba.edit', compact('pendaftar', 'daftarDosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'dosen_id' => 'required|exists:users,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $pendaftar = PendaftaranLombas::find($id);
            if ($pendaftar) {
                // Update status pendaftar
                $pendaftar->update([
                    'status' => 'accepted',
                    'dosen_id' => $request->dosen_id,
                ]);

                $mahasiswa_id = $pendaftar->user_id;

                // Cek apakah sudah ada bimbingan untuk mahasiswa ini
                $bimbingan = \App\Models\Bimbingan::where('mahasiswa_id', $mahasiswa_id)->first();
                if ($bimbingan) {
                    $bimbingan->update([
                        'dosen_id' => $request->dosen_id,
                    ]);
                } else {
                    \App\Models\Bimbingan::create([
                        'mahasiswa_id' => $mahasiswa_id,
                        'dosen_id' => $request->dosen_id,
                        'tanggal_mulai' => now(),
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data Pendaftar Berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data pendaftar tidak ditemukan'
                ]);
            }
        }
        return redirect('/manajemen/pendaftaranLomba');
    }

    public function confirm(string $id)
    {
        $pendaftar = PendaftaranLombas::find($id);

        if ($pendaftar) {
            return view('pendaftaranLomba.confirm', compact('pendaftar'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $pendaftar = PendaftaranLombas::find($id);

            if ($pendaftar) {
                try {
                    $pendaftar->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak dapat dihapus karena masih terdapat tabel lain yang terkait dengan data ini'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
