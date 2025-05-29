<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodi = ProgramStudi::all();

        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list'  => ['Home', 'Program Studi']
        ];

        return view('prodi.index', compact('breadcrumb', 'prodi'));
    }

    public function list(Request $request)
    {
        $prodi = ProgramStudi::select('id', 'name', 'jurusan');

        if ($request->prodi_id) {
            $prodi->where('id', $request->prodi_id);
        }


        return DataTables::of($prodi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($prodi) {
                $btn = '<button onclick="modalAction(\'' . route('prodis.show', $prodi->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('prodis.edit', $prodi->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('prodis.confirm', $prodi->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
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
        $prodi = ProgramStudi::all();

        return view('prodi.create', compact('prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name'      => 'required|string|max:255',
                'jurusan'   => 'required|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            ProgramStudi::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Program Studi berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $prodi = ProgramStudi::find($id);

        return view('prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $prodi = ProgramStudi::find($id);

        return view('prodi.edit', compact('prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'name'      => 'required|string|max:255',
                'jurusan'   => 'required|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = ProgramStudi::find($id);
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
        $prodi = ProgramStudi::find($id);

        return view('prodi.confirm', compact('prodi'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id, Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $prodi = ProgramStudi::find($id);

            if ($prodi) {
                try {
                    $prodi->delete();
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
