<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\Lomba;
use App\Models\User;
use App\Models\BuktiPrestasi;
use App\Models\TingkatanLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tingkatanLomba = TingkatanLomba::all();
        $breadcrumb = (object) [
            'title' => 'Prestasi',
            'list'  => ['Home', 'Prestasi']
        ];

        $activeMenu = 'prestasi';

        return view('prestasi.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'tingkatanLomba' => $tingkatanLomba,
        ]);
    }


    /**
     * Show the form for creating a new resource. (Ini sebenarnya untuk DataTables AJAX)
     */
    public function list(Request $request)
    {
        $userId = auth()->id();

        $data = Prestasi::with('user.detailUser')
            ->select('id', 'mahasiswa_id', 'nama_lomba', 'penyelenggara', 'kategori', 'pencapaian', 'status_verifikasi')
            ->where('mahasiswa_id', $userId)
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori', $request->kategori);
            });

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lomba', fn($row) => $row->nama_lomba)
            ->addColumn('penyelenggara', fn($row) => $row->penyelenggara ?? '-')
            ->addColumn('kategori', fn($row) => $row->kategori ?? '-')
            ->addColumn('pencapaian', fn($row) => $row->pencapaian ?? '-')
            ->addColumn('status', function ($row) {
                return match ($row->status_verifikasi) {
                    'verified' => '<span class="badge badge-success" style="font-size: 12px;">Disetujui</span>',
                    'rejected' => '<span class="badge badge-danger" style="font-size: 12px;">Ditolak</span>',
                    default    => '<span class="badge badge-warning" style="font-size: 12px;">Menunggu</span>',
                };
            })
            ->addColumn('aksi', function ($row) {
                return '
                <div class="d-flex justify-content-center align-items-center" style="gap: 2px;">
                    <button onclick="modalAction(\'' . route('prestasi.show', $row->id) . '\')" class="btn btn-info btn-sm">Detail</button>
                    <a href="' . route('prestasi.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <button onclick="modalAction(\'' . route('prestasi.confirm', $row->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>
                </div>
            ';
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
            'title' => 'Tambah Prestasi Baru',
            'list'  => ['Home', 'Prestasi', 'Create']
        ];

        $tingkatan = TingkatanLomba::all();

        return view('prestasi.create', compact('tingkatan', 'breadcrumb'));
    }

    /**
     * Store a newly created resource via AJAX.
     */

    public function store(Request $request)
    {
        $mahasiswaId = auth()->id();

        // Validasi
        $rules = [
            'nama_lomba'       => 'required|string|max:255',
            'penyelenggara'    => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal'          => 'required|date',
            'kategori'         => 'required|string|max:255',
            'pencapaian'       => 'required|string|max:255',
            'evaluasi_diri'    => 'nullable|string',
            'status_verifikasi' => 'required|in:pending,verified,rejected',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Validasi gagal saat menyimpan prestasi.', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->except(['bukti']),
            ]);
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            Log::info('Membuat data prestasi.', [
                'user_id' => $mahasiswaId,
                'input'   => $request->except(['bukti']),
            ]);

            $prestasi = Prestasi::create([
                'mahasiswa_id'      => $mahasiswaId,
                'nama_lomba'        => $request->nama_lomba,
                'penyelenggara'     => $request->penyelenggara,
                'deskripsi'         => $request->deskripsi,
                'tanggal'           => $request->tanggal,
                'kategori'          => $request->kategori,
                'pencapaian'        => $request->pencapaian,
                'evaluasi_diri'     => $request->evaluasi_diri,
                'status_verifikasi' => $request->status_verifikasi,
            ]);

            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $folder = 'bukti_prestasi/' . auth()->id();
                    $path = $file->storeAs("public/{$folder}", $fileName);

                    $prestasi->bukti()->create([
                        'prestasi_id'    => $prestasi->id,
                        'jenis_dokumen'  => 'bukti_prestasi',
                        'nama_file'      => $fileName,
                        'path_file'      => "{$folder}/{$fileName}",
                        'tanggal_upload' => now(),
                    ]);
                }
            }

            DB::commit();

            Log::info('Prestasi berhasil disimpan.', ['prestasi_id' => $prestasi->id]);

            return redirect()->route('prestasi.index')->with([
                'success' => 'Data prestasi berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan prestasi.', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ]);

            return redirect()->back()->withInput()->with([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
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
        $breadcrumb = (object) [
            'title' => 'Edit Prestasimu',
            'list'  => ['Home', 'Prestasi', 'Edit']
        ];

        $prestasi = Prestasi::find($id);
        $tingkatan = TingkatanLomba::all();

        if ($prestasi) {
            return view('prestasi.edit', compact('prestasi', 'tingkatan', 'breadcrumb'));
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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lomba'     => 'required|string|max:255',
            'penyelenggara'  => 'required|string|max:255',
            'kategori'       => 'required|string|max:100',
            'tanggal'        => 'required|date',
            'pencapaian'     => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'evaluasi_diri'  => 'nullable|string',
            'bukti.*'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $prestasi = Prestasi::findOrFail($id);
            $prestasi->fill($validator->validated());
            $prestasi->save();

            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $folder = 'bukti_prestasi/' . auth()->id();
                    $path = $file->storeAs("public/{$folder}", $fileName);

                    $prestasi->bukti()->create([
                        'prestasi_id'    => $prestasi->id,
                        'jenis_dokumen'  => 'bukti_prestasi',
                        'nama_file'      => $fileName,
                        'path_file'      => "{$folder}/{$fileName}",
                        'tanggal_upload' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui data prestasi', [
                'error_message' => $e->getMessage(),
                'stack_trace'   => $e->getTraceAsString(),
                'user_id'       => auth()->id(),
                'prestasi_id'   => $id,
                'request_data'  => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui prestasi.');
        }
    }


    public function confirm(string $id)
    {
        $prestasi = Prestasi::with(['user'])->find($id);

        if ($prestasi) {
            return view('prestasi.confirm', compact('prestasi'));
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
    public function destroy($id)
    {
        try {
            $prestasi = Prestasi::with('bukti')->findOrFail($id);

            DB::beginTransaction();

            if ($prestasi->bukti && $prestasi->bukti->count()) {
                foreach ($prestasi->bukti as $bukti) {
                    if (Storage::disk('public')->exists($bukti->path_file)) {
                        Storage::disk('public')->delete($bukti->path_file);
                    }

                    $bukti->delete();
                }
            }


            $prestasi->delete();

            DB::commit();

            Log::info('Prestasi berhasil dihapus', [
                'user_id'     => auth()->id(),
                'prestasi_id' => $id,
                'deleted_at'  => now(),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Prestasi berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus prestasi', [
                'user_id'     => auth()->id(),
                'prestasi_id' => $id,
                'message'     => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan saat menghapus prestasi.',
            ], 500);
        }
    }

    //  VERIFIKASI PRESTASI
    //======================================================================================

    public function index_verif()
    {
        $verifPrestasi = Prestasi::where('status_verifikasi', 'pending')->get();

        $breadcrumb = (object) [
            'title' => 'Verifikasi Prestasi Mahasiswa',
            'list'  => ['Home', 'Verifikasi Prestasi']
        ];

        return view('verifPrestasi.index', compact('breadcrumb', 'verifPrestasi'));
    }

    public function show_verif(String $id)
    {
        $verifPrestasi = Prestasi::where('status_verifikasi', 'pending')
            ->where('id', $id)
            ->with(['user.detailUser.prodi', 'bukti'])
            ->first();

        if (!$verifPrestasi) {
            return redirect()->route('verifPres.index')
                ->with('error', 'Data prestasi tidak ditemukan atau sudah diverifikasi');
        }

        $buktiPrestasi = BuktiPrestasi::where('prestasi_id', $id)->get();

        return view('verifPrestasi.show', compact('verifPrestasi', 'buktiPrestasi'));
    }

    public function list_verif(Request $request)
    {
        $prestasis = Prestasi::select('id', 'mahasiswa_id', 'nama_lomba', 'penyelenggara', 'kategori', 'pencapaian', 'deskripsi', 'tanggal', 'status_verifikasi')
            ->where('status_verifikasi', 'pending')
            ->with(['user:id,email']);

        // Search umum
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search['value'] . '%';
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
            ->addColumn('nama_mahasiswa', function ($prestasi) {
                return $prestasi->user->detailUser->name ?? '-';
            })
            ->addColumn('nama_lomba', function ($prestasi) {
                return $prestasi->nama_lomba;
            })
            ->addColumn('penyelenggara', function ($prestasi) {
                return $prestasi->penyelenggara;
            })
            ->addColumn('pres', function ($prestasi) {
                return $prestasi->pencapaian;
            })
            ->addColumn('status', function () {
                return '<span class="badge badge-warning">Pending</span>';
            })
            ->addColumn('aksi', function ($prestasi) {
                return '<button onclick="modalAction(\'' . route('verifPres.show', $prestasi->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
            })
            ->rawColumns(['status', 'aksi'])
            ->make(true);
    }

    public function updateStatus(Request $request, String $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'status_verifikasi' => 'required|in:verified,rejected',
                'catatan' => 'nullable|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Prestasi::find($id);
            if ($check) {
                $check->update([
                    'status_verifikasi' => $request->status_verifikasi,
                    'catatan'           => $request->catatan
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Status verifikasi berhasil diperbarui'
                ]);
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
