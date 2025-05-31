<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Lomba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Eager load relasi 'lomba' dan 'user'
        $prestasis = Prestasi::with(['lomba', 'user']);

        // Jika Anda ingin filter di tampilan awal index, tambahkan logika ini
        if ($request->filled('kategori_filter')) {
            $prestasis->where('kategori', $request->kategori_filter);
        }
        if ($request->filled('status_filter')) {
            $prestasis->where('status_verifikasi', $request->status_filter);
        }

        // Anda bisa mendapatkan daftar kategori unik dari data yang ada untuk dropdown filter
        // $kategoris = Prestasi::select('kategori')->distinct()->pluck('kategori');

        $breadcrumb = (object) [
            'title' => 'Prestasi',
            'list'  => ['Home', 'Prestasi']
        ];

        $page = (object) [
            'title' => 'Prestasi',
            'subtitle' => 'Daftar Prestasi',
        ];

        $activeMenu = 'prestasi';

        return view('prestasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'prestasis' => $prestasis->get() 
        ]);
    }


    /**
     * Show the form for creating a new resource. (Ini sebenarnya untuk DataTables AJAX)
     */
    public function list(Request $request)
    {
        $prestasis = Prestasi::select('id', 'mahasiswa_id', 'lomba_id', 'nama_kegiatan', 'kategori', 'pencapaian', 'deskripsi', 'tanggal')
                            ->with(['lomba:id,judul,penyelenggara', 'user:id,email']);

        // Filter berdasarkan kategori_filter jika ada (sesuaikan dengan nama di AJAX data)
        if ($request->filled('kategori_filter')) {
            $prestasis->where('kategori', $request->kategori_filter);
        }

        // Filter Berdasarkan Status_filter jika ada
        if ($request->filled('status_filter')) {
            $prestasis->where('status_verifikasi', $request->status_filter);
        }

        // Search umum
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search['value'] . '%'; // Ambil nilai search dari DataTables
            $prestasis->where(function ($q) use ($searchTerm) {
                $q->where('nama_kegiatan', 'like', $searchTerm)
                  ->orWhere('kategori', 'like', $searchTerm)
                  ->orWhere('pencapaian', 'like', $searchTerm)
                  ->orWhere('deskripsi', 'like', $searchTerm)
                  ->orWhereHas('lomba', function ($qLomba) use ($searchTerm) {
                      $qLomba->where('judul', 'like', $searchTerm)
                             ->orWhere('penyelenggara', 'like', $searchTerm);
                  })
                  ->orWhereHas('user', function ($qUser) use ($searchTerm) {
                      $qUser->where('email', 'like', $searchTerm);
                  });
            });
        }


        return DataTables::of($prestasis)
            ->addIndexColumn()
            // ->addColumn('email', function ($prestasi) { // Tambah kolom mahasiswa
            //     return $prestasi->user ? $prestasi->user->email : '-';
            // })
            ->addColumn('judul_lomba', function ($prestasi) {
                return $prestasi->lomba ? $prestasi->lomba->judul : '-';
            })
            ->addColumn('penyelenggara', function ($prestasi) {
                return $prestasi->lomba ? $prestasi->lomba->penyelenggara : '-';
            })
            // Kolom 'kategori' sudah ada di select
            ->addColumn('pres', function ($prestasi) { // Asumsi ini adalah kolom yang ingin ditampilkan
                return $prestasi->pencapaian;
            })
            ->addColumn('status', function ($prestasi) {
                if ($prestasi->status_verifikasi == 'approved') {
                    return '<span class="badge badge-success">Disetujui</span>';
                } elseif ($prestasi->status_verifikasi == 'rejected') {
                    return '<span class="badge badge-danger">Ditolak</span>';
                } else {
                    return '<span class="badge badge-warning">Pending</span>';
                }
            })
            ->addColumn('aksi', function ($prestasi) {
                $btn = '<button onclick="modalAction(\'' . route('prestasi.show', $prestasi->id) . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . route('prestasi.edit', $prestasi->id) . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . route('prestasi.confirm', $prestasi->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource via AJAX.
     */
    public function create()
    {
        $lombas = Lomba::all();
        $mahasiswas = User::where('level_id', 'mahasiswa')->get();

        return view('prestasi.create', compact('lombas', 'mahasiswas'));
    }

    /**
     * Store a newly created resource via AJAX.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|exists:users,id',
                'lomba_id' => 'required|exists:lombas,id',
                'nama_kegiatan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:255',
                'pencapaian' => 'required|string|max:255',
                'evaluasi_diri' => 'nullable|string',
                'status_verifikasi' => 'required|in:pending,approved,rejected',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            Prestasi::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data prestasi berhasil disimpan'
            ]);
        }

        return redirect('/'); // Fallback jika bukan AJAX
    }

    /**
     * Display the specified resource via AJAX.
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with(['lomba', 'user'])->find($id);

        if ($prestasi) {
            return view('prestasi.show', compact('prestasi'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource via AJAX.
     */
    public function edit(string $id)
    {
        $prestasi = Prestasi::find($id);
        $lombas = Lomba::all();
        $mahasiswas = User::where('level_id', 'mahasiswa')->get();

        if ($prestasi) {
            return view('prestasi.edit', compact('prestasi', 'lombas', 'mahasiswas'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource via AJAX.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|exists:users,id',
                'lomba_id' => 'required|exists:lombas,id',
                'nama_kegiatan' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:255',
                'pencapaian' => 'required|string|max:255',
                'evaluasi_diri' => 'nullable|string',
                'status_verifikasi' => 'required|in:pending,approved,rejected',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $prestasi = Prestasi::find($id);
            if ($prestasi) {
                $prestasi->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data prestasi berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/'); // Fallback jika bukan AJAX
    }

    /**
     * Show the form for deleting the specified resource via AJAX.
     */
    public function confirm(string $id)
    {
        $prestasi = Prestasi::with(['lomba', 'user'])->find($id);

        if ($prestasi) {
            return view('prestasi.delete', compact('prestasi'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Remove the specified resource via AJAX.
     */
    public function destroy(string $id)
    {
        if (request()->ajax()) {
            $prestasi = Prestasi::find($id);

            if ($prestasi) {
                $prestasi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data prestasi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/'); // Fallback jika bukan AJAX
    }
}