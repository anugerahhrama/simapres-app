<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\Keahlian;
use App\Models\Lomba;
use App\Models\TingkatanLomba;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VerifLombaController extends Controller
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

        return view('verifLomba.index', compact('breadcrumb', 'tingkatanLomba'));
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
            })->where('status_verifikasi', 'pending')->with('createdBy');

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
            ->addColumn('createdBy', function ($row) {
                return $row->createdBy->detailUSer->name ?? '-';
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
                $btn .= '<button onclick="modalAction(\'' . route('verifLomba.show', $row->id) . '\')" class="btn btn-info btn-sm">Detail</button>';
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
        $lomba = Lomba::where('id', $id)->first();
        $lomba->load(['tingkatanLomba', 'keahlian', 'minat', 'createdBy']);
        $detailUser = DetailUser::all();
        $users = User::with('detailUser')->get();
        $tingkatanLombas = TingkatanLomba::all();
        $keahlians = Keahlian::all();

        return view('verifLomba.show', compact('lomba', 'tingkatanLombas', 'keahlians', 'detailUser', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'status_verifikasi' => 'required|in:pending,verified,rejected',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Validasi gagal. Silakan periksa form Anda.');
            }

            $lomba = Lomba::where('id', $id)->first();

            try {
                DB::beginTransaction();

                $validated = $validator->validated();

                $lomba->update([
                    'status_verifikasi' => $validated['status_verifikasi'],
                ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data.'
                ]);
            }
        }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
