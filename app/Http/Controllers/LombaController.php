<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\TingkatanLomba; 
use App\Models\Keahlian;       
use App\Models\Minat;          
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator; 
use Yajra\DataTables\Facades\DataTables; 

class LombaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan halaman utama daftar lomba.
     */
    public function index()
    {
        // Data untuk filter dropdown (jika ada)
        // Contoh: Ambil semua kategori unik dari lomba yang sudah ada
        $kategoris = Lomba::select('kategori')->distinct()->pluck('kategori');
        $tingkatanLombas = TingkatanLomba::all(); // Untuk filter tingkatan

        $breadcrumb = (object) [
            'title' => 'Daftar Lomba',
            'list'  => ['Home', 'Lomba']
        ];

        return view('lombas.index', compact('breadcrumb', 'kategoris', 'tingkatanLombas'));
    }

    /**
     * Mengambil data lomba untuk DataTables via AJAX.
     */
     public function list(Request $request){
        $query = Lomba::select(
            'id',
            'judul',
            'kategori',
            'penyelenggara',
            'awal_registrasi',
            'akhir_registrasi',
            'status_verifikasi',
            'tingkatan_lomba_id',
            'bidang_keahlian_id',
            'minat_id'
        )->with([
            'tingkatanLomba:id,nama_tingkatan', // Ambil hanya id dan nama_tingkatan
            'keahlian:id,nama_keahlian',         // Ambil hanya id dan nama_keahlian
            'minat:id,nama_minat'                // Ambil hanya id dan nama_minat
        ]);

        // Filter berdasarkan kategori
        if ($request->filled('kategori_filter')) {
            $query->where('kategori', $request->kategori_filter);
        }

        // Filter berdasarkan tingkatan_lomba_id
        if ($request->filled('tingkatan_filter')) {
            $query->where('tingkatan_lomba_id', $request->tingkatan_filter);
        }

        // Filter berdasarkan status_verifikasi
        if ($request->filled('status_filter')) {
            $query->where('status_verifikasi', $request->status_filter);
        }

        // Search umum (sesuai dengan input pencarian dari frontend)
        // Frontend sekarang mengirim 'search' langsung, bukan 'search[value]'
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%'; // Ambil nilai search langsung
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'like', $searchTerm)
                  ->orWhere('penyelenggara', 'like', $searchTerm)
                  ->orWhere('kategori', 'like', $searchTerm)
                  ->orWhere('status_verifikasi', 'like', $searchTerm)
                  ->orWhereHas('tingkatanLomba', function ($qRel) use ($searchTerm) {
                      $qRel->where('nama_tingkatan', 'like', $searchTerm);
                  })
                  ->orWhereHas('keahlian', function ($qRel) use ($searchTerm) {
                      $qRel->where('nama_keahlian', 'like', $searchTerm);
                  })
                  ->orWhereHas('minat', function ($qRel) use ($searchTerm) {
                      $qRel->where('nama_minat', 'like', $searchTerm);
                  });
            });
        }

        // Ambil data dengan pagination Laravel
        // Default per page bisa disesuaikan, atau diambil dari request jika ada
        $perPage = $request->input('per_page', 10); // Ambil per_page dari request, default 10
        $lombas = $query->paginate($perPage);

        // Format data agar sesuai dengan kebutuhan frontend (untuk tampilan kartu)
        $formattedLombas = $lombas->map(function($lomba) {
            return [
                'id' => $lomba->id,
                'judul' => $lomba->judul,
                'kategori' => $lomba->kategori,
                'penyelenggara' => $lomba->penyelenggara,
                'awal_registrasi' => $lomba->awal_registrasi,
                'akhir_registrasi' => $lomba->akhir_registrasi,
                'status_verifikasi' => $lomba->status_verifikasi,
                // Tambahkan nama relasi langsung ke dalam array data
                'tingkatan_nama' => $lomba->tingkatanLomba->nama_tingkatan ?? 'N/A',
                'keahlian_nama' => $lomba->keahlian->nama_keahlian ?? 'N/A',
                'minat_nama' => $lomba->minat->nama_minat ?? 'N/A',
            ];
        });

        // Kembalikan respons JSON dengan data dan metadata pagination
        return response()->json([
            'data' => $formattedLombas,
            'meta' => [
                'current_page' => $lombas->currentPage(),
                'from' => $lombas->firstItem(),
                'last_page' => $lombas->lastPage(),
                'path' => $lombas->path(),
                'per_page' => $lombas->perPage(),
                'to' => $lombas->lastItem(),
                'total' => $lombas->total(),
            ],
            // 'links' => $lombas->linkCollection(), // Opsional: jika Anda ingin menyertakan link pagination lengkap
        ]);
    }



    /**
     * Show the form for creating a new resource.
     * Menampilkan formulir untuk membuat lomba baru (via modal AJAX).
     */
    public function create()
    {
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();
        $minats = Minat::all();

        return view('lombas.create', compact('tingkatanLombas', 'keahlians', 'minats'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan lomba baru ke database (via AJAX).
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string|in:Akademik,Non Akademik',
                'deskripsi' => 'required|string',
                'penyelenggara' => 'required|string|max:255',
                'link_registrasi' => 'nullable|url|max:255',
                'awal_registrasi' => 'required|date',
                'akhir_registrasi' => 'required|date|after_or_equal:awal_registrasi',
                'tingkatan_lomba_id' => 'required|exists:tingkatan_lombas,id',
                'bidang_keahlian_id' => 'required|exists:keahlians,id',
                'minat_id' => 'required|exists:minats,id',
                'status_verifikasi' => 'required|string|in:pending,approved,rejected',
                'jenis_pendaftaran' => 'required|string|in:Online,Offline',
                'harga_pendaftaran' => 'required|numeric|min:0',
                'perkiraan_hadiah' => 'nullable|string|max:255',
                'mendapatkan_uang' => 'boolean',
                'mendapatkan_sertifikat' => 'boolean',
                'nilai_benefit' => 'required|integer|min:0|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $validatedData = $request->all();
            $validatedData['created_by'] = Auth::id(); // Tambahkan created_by dari user yang sedang login

            Lomba::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Data Lomba berhasil disimpan'
            ]);
        }
        return redirect('/'); 
    }

    /**
     * Display the specified resource.
     * Menampilkan detail lomba tertentu (via modal AJAX).
     */
    public function show(string $id)
    {
        $lomba = Lomba::with(['tingkatanLomba', 'keahlian', 'minat', 'pendaftaranLomba'])->find($id);

        if ($lomba) {
            return view('lombas.show', compact('lomba'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan formulir untuk mengedit lomba tertentu (via modal AJAX).
     */
    public function edit(string $id)
    {
        $lomba = Lomba::find($id);
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();
        $minats = Minat::all();

        if ($lomba) {
            return view('lombas.edit', compact('lomba', 'tingkatanLombas', 'keahlians', 'minats'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui lomba tertentu di database (via AJAX).
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'judul' => 'required|string|max:255',
                'kategori' => 'required|string|in:Akademik,Non Akademik',
                'deskripsi' => 'required|string',
                'penyelenggara' => 'required|string|max:255',
                'link_registrasi' => 'nullable|url|max:255',
                'awal_registrasi' => 'required|date',
                'akhir_registrasi' => 'required|date|after_or_equal:awal_registrasi',
                'tingkatan_lomba_id' => 'required|exists:tingkatan_lombas,id',
                'bidang_keahlian_id' => 'required|exists:keahlians,id',
                'minat_id' => 'required|exists:minats,id',
                'status_verifikasi' => 'required|string|in:pending,approved,rejected',
                'jenis_pendaftaran' => 'required|string|in:Online,Offline',
                'harga_pendaftaran' => 'required|numeric|min:0',
                'perkiraan_hadiah' => 'nullable|string|max:255',
                'mendapatkan_uang' => 'boolean',
                'mendapatkan_sertifikat' => 'boolean',
                'nilai_benefit' => 'required|integer|min:0|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $lomba = Lomba::find($id);
            if ($lomba) {
                $lomba->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data Lomba berhasil diupdate'
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
     * Menampilkan konfirmasi hapus lomba (via modal AJAX).
     */
    public function confirm(string $id)
    {
        $lomba = Lomba::find($id);

        if ($lomba) {
            return view('lombas.confirm', compact('lomba'));
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus lomba tertentu dari database (via AJAX).
     */
    public function destroy(string $id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $lomba = Lomba::find($id);

            if ($lomba) {
                try {
                    $lomba->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data Lomba berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain (misal: prestasi, pendaftaran lomba).'
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