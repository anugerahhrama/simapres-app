<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Lomba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Eager load relasi 'lomba' dan 'user'
        $prestasis = Prestasi::with(['user']);

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
        ]);
    }


    /**
     * Show the form for creating a new resource. (Ini sebenarnya untuk DataTables AJAX)
     */
    public function list(Request $request)
    {
        $prestasis = Prestasi::select('id', 'mahasiswa_id', 'nama_lomba', 'penyelenggara', 'kategori', 'pencapaian', 'deskripsi', 'tanggal')
                            ->with(['user:id,email']);

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
                $q->where('nama_lomba', 'like', $searchTerm)
                  ->orWhere('kategori', 'like', $searchTerm)
                  ->orWhere('pencapaian', 'like', $searchTerm)
                  ->orWhere('deskripsi', 'like', $searchTerm)
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
            ->addColumn('nama_lomba', function ($prestasi) {
                return $prestasi->nama_lomba;
            })
            ->addColumn('penyelenggara', function ($prestasi) {
                return $prestasi->penyelenggara;
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
                $btn = '<div class="d-flex justify-content-start">';
                $btn .= '<a href="'.url('prestasi/' .$prestasi->id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('prestasi/' .$prestasi->id. '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="inline-block" action="' . url('prestasi/' . $prestasi->id) . '" method="POST">'
                        . csrf_field() 
                        . method_field('DELETE') 
                        . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus prestasi ini?\')">Hapus</button>'
                        . '</form>';
                $btn .= '</div>';
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
         $breadcrumb = (object) [
            'title' => 'Prestasi',
            'list'  => ['Home', 'Prestasi']
        ];

        $page = (object) [
            'title' => 'Prestasi',
            'subtitle' => 'Daftar Prestasi',
        ];

        $activeMenu = 'prestasi';
        
        $lombas = Lomba::all();

        return view('prestasi.create', compact('lombas', 'breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Store a newly created resource via AJAX.
     */

   public function store(Request $request)
    {
    // Definisikan aturan validasi
    $rules = [
        'mahasiswa_id' => 'required|exists:users,id',
        'nama_lomba' => 'required|string|max:255',
        'penyelenggara' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'tanggal' => 'required|date',
        'kategori' => 'required|string|max:255',
        'pencapaian' => 'required|string|max:255',
        'evaluasi_diri' => 'nullable|string',
        'status_verifikasi' => 'required|in:pending,approved,rejected',
        'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    // Validasi request
    $validator = Validator::make($request->all(), $rules);

    // Jika validasi gagal
    if ($validator->fails()) {
        Log::warning('Validasi gagal pada input prestasi.', [
            'errors' => $validator->errors(),
            'input' => $request->all()
        ]);
        return redirect()->back()->withInput()->with('errors', $validator->errors());
    }

    DB::beginTransaction(); // Mulai transaksi DB

    try {
        // Simpan data prestasi
        Log::info('Menyimpan data prestasi.', ['data' => $request->all()]);
        $prestasi = Prestasi::create($request->all());

        // Cek apakah ada file bukti yang diunggah
        if ($request->hasFile('bukti')) {
            Log::info('File bukti ditemukan. Mengunggah file...', ['file' => $request->file('bukti')->getClientOriginalName()]);
            $file = $request->file('bukti');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/bukti_prestasi', $fileName);  // Menggunakan 'storeAs' untuk menyimpan file

            // Menyimpan data bukti ke tabel bukti terkait prestasi
            $prestasi->bukti()->create([
                'prestasi_id' => $prestasi->id,
                'jenis_dokumen' => 'bukti_prestasi', // Sesuaikan jenis dokumen
                'nama_file' => $fileName,
                'path_file' => $path,
                'tanggal_upload' => now(),
            ]);
            Log::info('File bukti berhasil diunggah dan disimpan.', ['file_name' => $fileName, 'path' => $path]);
        }

        DB::commit(); // Jika semua berhasil, commit perubahan ke DB

        Log::info('Data prestasi berhasil disimpan.', ['prestasi_id' => $prestasi->id]);

        return redirect()->route('prestasi.index')->with([
            'status' => true,
            'message' => 'Data prestasi berhasil disimpan',
        ]);
    } catch (\Exception $e) {
        DB::rollBack(); // Jika terjadi error, rollback transaksi

        Log::error('Terjadi kesalahan saat menyimpan data prestasi.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTrace(),
            'request_data' => $request->all(),
        ]);

        // Mengembalikan error ke view
        return redirect()->back()->withInput()->with([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ]);
    }
}


    /**
     * Display the specified resource via AJAX.
     */
    public function show(string $id)
    {
        $prestasi = Prestasi::with(['user'])->find($id);

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
            $rules = [
                'mahasiswa_id' => 'required|exists:users,id',
                'nama_lomba' => 'required|string|max:255',
                'penyelenggara' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
                'kategori' => 'required|string|max:255',
                'pencapaian' => 'required|string|max:255',
                'evaluasi_diri' => 'nullable|string',
                'status_verifikasi' => 'required|in:pending,approved,rejected',
                'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->with
                ('errors', $validator->errors());
            }

            $prestasi = Prestasi::find($id);
            if ($prestasi) {
                $prestasi->update($request->all());
                return redirect()->route('prestasi.index')->with([
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

    public function confirm(string $id)
    {
        $prestasi = Prestasi::with([ 'user'])->find($id);

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
    // Jika permintaan adalah AJAX
    if (request()->ajax()) {
        $prestasi = Prestasi::find($id);

        if ($prestasi) {
            $prestasi->delete();  // Menghapus data prestasi

            // Mengembalikan respons JSON sukses jika data ditemukan dan dihapus
            return response()->json([
                'status' => true,
                'message' => 'Data prestasi berhasil dihapus'
            ]);
        } else {
            // Jika data tidak ditemukan
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    // Jika permintaan bukan AJAX, alihkan ke halaman index atau rute yang sesuai
    $prestasi = Prestasi::findOrFail($id);  // Jika Anda ingin melemparkan pengecualian jika data tidak ditemukan
    $prestasi->delete();  // Menghapus data

    // Redirect ke halaman index atau halaman lain yang sesuai
    return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil dihapus');
    }
}
