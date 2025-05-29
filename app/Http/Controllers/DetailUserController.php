<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DetailUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Detail User',
            'list'  => ['Home', 'Detail User']
        ];

        $prodis = ProgramStudi::all(); // Ambil semua data prodi dari database
        return view('detail_users.index', compact('breadcrumb', 'prodis'));
    }

    public function list(Request $request)
    {
        $data = DetailUser::with('detailUser', 'prodi');

        if ($request->prodi_id) {
            $data->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', fn($row) => $row->detailUser?->id ?? '-')
            ->addColumn('email', fn($row) => $row->detailUser->email ?? '-')
            ->addColumn('prodi', fn($row) => $row->prodi->id ?? '-')
            ->addColumn('aksi', function ($row) {
                $btn = '<button onclick="modalAction(\'' . route('detailusers.show', $row->id) . '\')" class="btn btn-info btn-sm mr-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . route('detailusers.edit', $row->id) . '\')" class="btn btn-warning btn-sm mr-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . route('detailusers.confirm', $row->id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
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
        $prodis = ProgramStudi::all();
        $users = detailUser::all();
        return view('detail_users.create', compact('prodis', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'no_induk' => 'required|string|max:20|unique:detail_users,no_induk',
            'prodi_id' => 'required|exists:program_studis,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DetailUser::create($request->all());

        return redirect()->route('detailusers.index')->with('success', 'Detail user berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detailUser = DetailUser::with('detailUser', 'prodi')->findOrFail($id);
        return view('detail_users.show', compact('detailUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $detailUser = DetailUser::findOrFail($id);
        $prodis = ProgramStudi::all();
        return view('detail_users.edit', compact('detailUser', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detailUser = DetailUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'no_induk' => 'required|string|max:20|unique:detail_users,no_induk,' . $id,
            'prodi_id' => 'required|exists:program_studis,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $detailUser->update($request->all());

        return redirect()->route('detailusers.index')->with('success', 'Detail user berhasil diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function confirm(string $id)
    {
        $detailUser = DetailUser::with('detailUser', 'prodi')->findOrFail($id);
        return view('detail_users.confirm', compact('detailUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy(string $id, Request $request)
    {
     $detailUser = DetailUser::findOrFail($id);
        $detailUser->delete();

        return redirect()->route('detailusers.index')->with('success', 'Detail user berhasil dihapus.');   
    }
}
