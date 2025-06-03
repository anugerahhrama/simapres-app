<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LombaController extends Controller
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

    $prodis = ProgramStudi::all(); 

    return view('lomba.index', compact('breadcrumb', 'prodis'));
    }

    public function list(Request $request)
    {
        $data = Lomba::with('prodi');

        if ($request->prodi_id) {
            $data->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('prodi', function ($row) {
                return $row->prodi->name ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . route('lomba.show', $row->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.edit', $row->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('lomba.confirm', $row->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
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
        return view('lomba.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'judul_lomba' => 'required|string|max:255',
        'tingkatan' => 'required|in:pemula,lokal,regional,nasional,internasional',
        'penyelenggara' => 'nullable|string|max:100',
        'deskripsi' => 'nullable|string|max:255',
        'prodi_id' => 'required|exists:program_studis,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal!',
                'msgField' => $validator->errors()
            ]);
        }

        Lomba::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Lomba berhasil ditambahkan!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lomba $lomba)
    {
        $lomba->load('prodi');

        return view('lomba.show', compact('lomba'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lomba $lomba)
    {
        $prodis = ProgramStudi::all();

        return view('lomba.edit', compact('lomba', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lomba $lomba)
    {
        $validator = Validator::make($request->all(), [
        'judul_lomba' => 'required|string|max:255',
        'tingkatan' => 'required|in:pemula,lokal,regional,nasional,internasional',
        'penyelenggara' => 'nullable|string|max:100',
        'deskripsi' => 'nullable|string|max:255',
        'prodi_id' => 'required|exists:program_studis,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal!',
                'msgField' => $validator->errors()
            ]);
        }

        $lomba->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function confirm(Lomba $lomba)
    {
        return view('lomba.confirm', compact('lomba'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lomba $lomba)
    {
        $deletedData = [
            'judul_lomba' => $lomba->judul_lomba ?? '-',
            'tingkatan' => $lomba->tingkatan ?? '-',
            'penyelenggara' => $lomba->penyelenggara ?? '-',
            'deskripsi' => $lomba->deskripsi ?? '-',
            'prodi' => $lomba->prodi->name ?? '-',
        ];

        $lomba->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data lomba berhasil dihapus.',
            'deleted_data' => $deletedData
        ]);
    }

}
