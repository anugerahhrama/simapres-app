<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Bimbingan',
            'list'  => ['Home', 'Bimbingan']
        ];

        return view('bimbingan.index', compact('breadcrumb'));
    }

    public function list(Request $request)
    {
        $user = Auth::user();

        if ($user->level && $user->level->level_code == 'DSN') {
            $query = \App\Models\Bimbingan::with(['dosen', 'mahasiswa.detailUser'])
                ->where('dosen_id', $user->id)
                ->orderByDesc('id');
        } else if ($user->level && $user->level->level_code == 'ADM') {
            $query = \App\Models\Bimbingan::with(['dosen', 'mahasiswa.detailUser', 'pendaftaranLomba']);
        } 

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('mahasiswa', function ($bimbingan) {
                // Ambil nama dari detail user mahasiswa
                return $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser
                    ? $bimbingan->mahasiswa->detailUser->name
                    : '-';
            })
            ->addColumn('dosen', function ($bimbingan) {
                return $bimbingan->dosen && $bimbingan->dosen->detailUser
                    ? $bimbingan->dosen->detailUser->name
                    : '-';
            })
            ->addColumn('tanggal_mulai', function ($bimbingan) {
                return $bimbingan->tanggal_mulai ? date('d-m-Y', strtotime($bimbingan->tanggal_mulai)) : '-';
            })
            ->addColumn('tanggal_selesai', function ($bimbingan) {
                return $bimbingan->tanggal_selesai ? date('d-m-Y', strtotime($bimbingan->tanggal_selesai)) : '-';
            })
            ->addColumn('status', function ($bimbingan) {
                return $bimbingan->status;
            })
            ->addColumn('aksi', function ($bimbingan) {
                $user = Auth::user();
                $btn = '
                    <button onclick="modalAction(\''.route('bimbingan.show', $bimbingan->id).'\')" class="btn btn-info btn-sm" title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                ';
                if ($user && $user->level && $user->level->level_code == 'ADM') {
                    $btn .= '
                        <button onclick="modalAction(\''.route('bimbingan.edit', $bimbingan->id).'\')" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="modalAction(\''.route('bimbingan.confirm', $bimbingan->id).'\')" class="btn btn-danger btn-sm" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                }
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
        // Ambil semua user yang level-nya mahasiswa (misal level_code = 'MHS')
        $mahasiswa = \App\Models\User::whereHas('detailUser')
            ->whereHas('level', function($q) {
                $q->where('level_code', 'MHS');
            })
            ->with('detailUser')
            ->get();
        return view('bimbingan.create', compact('mahasiswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|exists:users,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'catatan_bimbingan' => 'nullable|string|max:255',
                'status' => 'required|in:1,2,3'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $dosenId = Auth::id();
            $mahasiswaId = $request->mahasiswa_id;

            // Cek duplikasi bimbingan untuk dosen dan mahasiswa yang sama
            $exists = \App\Models\Bimbingan::where('dosen_id', $dosenId)
                ->where('mahasiswa_id', $mahasiswaId)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mahasiswa tersebut sudah memiliki bimbingan dengan Anda.',
                    'msgField' => ['mahasiswa_id' => ['Mahasiswa sudah terdaftar pada bimbingan Anda.']]
                ]);
            }

            $data = $request->all();
            $data['dosen_id'] = $dosenId;

            Bimbingan::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Data Bimbingan berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Tambahkan eager loading mahasiswa.detailUser
        $bimbingan = Bimbingan::with(['dosen', 'mahasiswa.detailUser'])->findOrFail($id);
        return view('bimbingan.show', compact('bimbingan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bimbingan = Bimbingan::with(['mahasiswa.detailUser'])->findOrFail($id);
        // Ambil semua user yang level-nya mahasiswa (misal level_code = 'MHS')
        $mahasiswa = \App\Models\User::whereHas('detailUser')
            ->whereHas('level', function($q) {
                $q->where('level_code', 'MHS');
            })
            ->with('detailUser')
            ->get();
        return view('bimbingan.edit', compact('bimbingan', 'mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'mahasiswa_id' => 'required|exists:users,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
                'catatan_bimbingan' => 'nullable|string|max:255',
                'status' => 'required|in:1,2,3'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $bimbingan = Bimbingan::find($id);
            if ($bimbingan) {
                $data = $request->all();
                $data['dosen_id'] = Auth::id(); // Set dosen_id dari user login
                $bimbingan->update($data);
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
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

    /**
     * Remove the specified resource from storage.
     */
    public function confirm($id)
    {
        $bimbingan = Bimbingan::with(['dosen', 'mahasiswa'])->findOrFail($id);
        return view('bimbingan.confirm', compact('bimbingan'));
    }

    public function destroy($id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $bimbingan = Bimbingan::find($id);

            if ($bimbingan) {
                try {
                    $bimbingan->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException) {
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

    public function updateStatus(Request $request, String $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'status' => 'required|in:0,1,2',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Bimbingan::find($id);
            if ($check) {
                $check->update([
                    'status' => $request->status,
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Status Bimbingan berhasil diperbarui'
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
