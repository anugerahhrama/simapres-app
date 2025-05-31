<?php

namespace App\Http\Controllers;

use App\Models\Minat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MinatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $minats = Minat::all();

        $breadcrumb = (object) [
            'title' => 'Daftar Minat',
            'list'  => ['Home', 'Minat']
        ];

        return view('minat.index', compact('breadcrumb', 'minats'));
    }

    public function list(Request $request)
    {
        $minat = Minat::select('id', 'minat_code', 'nama_minat');

        if ($request->minat_id) {
            $minat->where('id', $request->minat_id);
        }

        return DataTables::of($minat)
            ->addIndexColumn()
            ->addColumn('aksi', function ($minat) {
                $btn = '<button onclick="modalAction(\'' . route('minats.show', $minat->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('minats.edit', $minat->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('minats.confirm', $minat->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
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
        $minat = Minat::all();

        return view('minat.create', compact('minat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'minat_code' => 'required|string|min:3|unique:minats,minat_code',
                'nama_minat' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Minat::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Minat berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $minat = Minat::find($id);

        return view('minat.show', compact('minat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $minat = Minat::find($id);

        return view('minat.edit', compact('minat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'minat_code'    => 'required|min:3',
                'nama_minat'    => 'required|string'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Minat::find($id);
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
        $minat = Minat::find($id);

        return view('minat.confirm', compact('minat'));
    }

    public function destroy(string $id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $minat = Minat::find($id);

            if ($minat) {
                try {
                    $minat->delete();
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