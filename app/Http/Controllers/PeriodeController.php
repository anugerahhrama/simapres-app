<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodes = Periode::all();

        $breadcrumb = (object) [
            'title' => 'Daftar Periode',
            'list'  => ['Home', 'Periode']
        ];

        return view('periode.index', compact('breadcrumb', 'periodes'));
    }

    public function list(Request $request)
    {
        $periode = Periode::select('id', 'tahun_ajaran', 'semester', 'tanggal_mulai', 'tanggal_selesai');

        if ($request->periode_id) {
            $periode->where('id', $request->periode_id);
        }


        return DataTables::of($periode)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) {
                $disabled = $periode->periode_code === 'ADM' ? 'disabled' : '';
                $btn = '<button onclick="modalAction(\'' . route('periodes.show', $periode->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('periodes.edit', $periode->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('periodes.confirm', $periode->id) . '\')" class="btn btn-danger btn-sm" ' . $disabled . '>Hapus</button>';
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
        $periode = Periode::all();

        return view('periode.create', compact('periode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tahun_ajaran' => 'required|integer',
                'semester' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Periode::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Periode berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $periode = Periode::find($id);

        return view('periode.show', compact('periode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $periode = Periode::find($id);

        return view('periode.edit', compact('periode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'tahun_ajaran'    => 'required|integer',
                'semester'    => 'required|string',
                'tanggal_mulai'    => 'required|date',
                'tanggal_selesai'    => 'required|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Periode::find($id);
            if ($check) {
                $check->update($request->all());
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
    public function confirm(string $id)
    {
        $periode = Periode::find($id);

        return view('periode.confirm', compact('periode'));
    }

    public function destroy(string $id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $periode = Periode::find($id);

            if ($periode) {
                try {
                    $periode->delete();
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
