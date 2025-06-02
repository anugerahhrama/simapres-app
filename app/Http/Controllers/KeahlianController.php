<?php

namespace App\Http\Controllers;

use App\Models\Keahlian; // Pastikan model Keahlian sudah ada dan diimpor
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables; // Untuk DataTables
use Illuminate\Support\Facades\Validator; // Untuk validasi

class KeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keahlians = Keahlian::all(); // Mengambil semua data keahlian

        $breadcrumb = (object) [
            'title' => 'Daftar Keahlian',
            'list'  => ['Home', 'Keahlian']
        ];

        // Mengirim data ke view 'keahlian.index'
        return view('keahlian.index', compact('breadcrumb', 'keahlians'));
    }

    /**
     * Menampilkan daftar data keahlian dalam format DataTables.
     */
    public function list(Request $request)
    {
        $keahlian = Keahlian::select('id', 'keahlian_code', 'nama_keahlian'); // Pilih kolom yang akan ditampilkan

        // Filter berdasarkan keahlian_id jika ada
        if ($request->keahlian_id) {
            $keahlian->where('id', $request->keahlian_id);
        }

        return DataTables::of($keahlian)
            ->addIndexColumn() // Menambahkan kolom nomor urut
            ->addColumn('aksi', function ($keahlian) {
                // Tombol aksi (Detail, Edit, Hapus)
                $btn = '<button onclick="modalAction(\'' . route('keahlians.show', $keahlian->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('keahlians.edit', $keahlian->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('keahlians.confirm', $keahlian->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Mengizinkan kolom 'aksi' menampilkan HTML
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keahlian = Keahlian::all(); // Mungkin tidak terlalu relevan di sini, tapi mengikuti pola LevelController

        // Mengirim data ke view 'keahlian.create'
        return view('keahlian.create', compact('keahlian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Memeriksa apakah request datang dari AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk kolom 'keahlian_code' dan 'nama_keahlian'
            $rules = [
                'keahlian_code' => 'required|string|min:3|unique:keahlians,keahlian_code', // keahlian_code harus unik di tabel 'keahlians'
                'nama_keahlian' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // Mengirim pesan error validasi
                ]);
            }

            // Membuat data keahlian baru
            Keahlian::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Keahlian berhasil disimpan'
            ]);
        }
        // Redirect ke halaman utama jika bukan request AJAX/JSON
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $keahlian = Keahlian::find($id); // Mencari data keahlian berdasarkan ID

        // Mengirim data ke view 'keahlian.show'
        return view('keahlian.show', compact('keahlian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $keahlian = Keahlian::find($id); // Mencari data keahlian berdasarkan ID

        // Mengirim data ke view 'keahlian.edit'
        return view('keahlian.edit', compact('keahlian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Memeriksa apakah request datang dari AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi untuk update
            $rules = [
                'keahlian_code'    => 'required|min:3',
                'nama_keahlian'    => 'required|string'
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Keahlian::find($id); // Mencari data keahlian yang akan diupdate
            if ($check) {
                $check->update($request->all()); // Melakukan update data
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
        // Redirect ke halaman utama jika bukan request AJAX/JSON
        return redirect('/');
    }

    /**
     * Menampilkan form konfirmasi penghapusan resource.
     */
    public function confirm(string $id)
    {
        $keahlian = Keahlian::find($id); // Mencari data keahlian yang akan dihapus

        // Mengirim data ke view 'keahlian.confirm'
        return view('keahlian.confirm', compact('keahlian'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        // Memeriksa apakah request datang dari AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            $keahlian = Keahlian::find($id); // Mencari data keahlian yang akan dihapus

            if ($keahlian) {
                try {
                    $keahlian->delete(); // Melakukan penghapusan data
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // Menangani error jika ada relasi dengan tabel lain
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
        // Redirect ke halaman utama jika bukan request AJAX/JSON
        return redirect('/');
    }
}