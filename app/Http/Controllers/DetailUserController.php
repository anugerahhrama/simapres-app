<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\Level;
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

        $prodis = ProgramStudi::all();
        $levels = Level::all();

        return view('detail_users.index', compact('breadcrumb', 'prodis', 'levels'));
    }

    public function list(Request $request)
    {
        $data = DetailUser::with(['detailUser.level', 'prodi']);

        if ($request->prodi_id) {
            $data->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return ($row->name && $row->name !== '') ? $row->name : '-';
            })
            ->addColumn('email', function ($row) {
                return $row->detailUser->email ?? '-';
            })
            ->addColumn('level', function ($row) {
                return $row->detailUser->level->nama_level ?? '-';
            })
            ->addColumn('prodi', function ($row) {
                return $row->prodi->name ?? '-';
            })
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
     * Display a listing of the resource.
     */
    public function create()
    {
        $prodis = ProgramStudi::all();
        $levels = Level::all();

        return view('detail_users.create', compact('prodis', 'levels'));
    }

    /**
     * Display a listing of the resource.
     */
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'no_induk' => 'required|string|max:20|unique:detail_users,no_induk',
                'name' => 'required|string|max:255',
                'prodi_id' => 'required|exists:program_studis,id',
                'level_id' => 'required|exists:levels,id',
                'phone' => 'nullable|string|max:20',
                'jenis_kelamin' => 'nullable|in:L,P',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'level_id' => $request->level_id
            ]);

            DetailUser::create([
                'user_id' => $user->id,
                'no_induk' => $request->no_induk,
                'name' => $request->name,
                'prodi_id' => $request->prodi_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data Detail User berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    /**
     * Display a listing of the resource.
     */
    public function show(string $id)
    {
        $detailUser = DetailUser::with('detailUser', 'prodi')->findOrFail($id);
        return view('detail_users.show', compact('detailUser'));
    }

    /**
     * Display a listing of the resource.
     */
    public function edit(string $id)
    {
        $detailUser = DetailUser::with('detailUser')->findOrFail($id);
        $prodis = ProgramStudi::all();
        $levels = Level::all();

        return view('detail_users.edit', compact('detailUser', 'prodis', 'levels'));
    }

    /**
     * Display a listing of the resource.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $detailUser = DetailUser::with('detailUser')->findOrFail($id);

            $rules = [
                'email' => 'required|email|unique:users,email,' . $detailUser->user_id,
                'no_induk' => 'required|string|max:20|unique:detail_users,no_induk,' . $id,
                'name' => 'required|string|max:255',
                'prodi_id' => 'required|exists:program_studis,id',
                'level_id' => 'required|exists:levels,id',
                'phone' => 'nullable|string|max:20',
                'jenis_kelamin' => 'nullable|in:L,P',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $user = $detailUser->detailUser;
            if ($user) {
                $user->update([
                    'email' => $request->email,
                    'level_id' => $request->level_id,
                ]);
            }

            $detailUser->update([
                'no_induk' => $request->no_induk,
                'name' => $request->name,
                'prodi_id' => $request->prodi_id,
                'phone' => $request->phone,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data Detail User berhasil diperbarui',
            ]);
        }

        return redirect()->route('detailusers.index')->with('error', 'Permintaan tidak valid');
    }

    /**
     * Display a listing of the resource.
     */
    public function confirm(string $id)
    {
        $detailUser = DetailUser::with(['detailUser', 'prodi'])->findOrFail($id);
        $prodis = ProgramStudi::all();
        $user = user::all();
        
        return view('detail_users.confirm', compact('detailUser'));
    }

    /**
     * Display a listing of the resource.
     */
    public function destroy(string $id)
    {
        $detailUser = DetailUser::with(['detailUser', 'prodi'])->findOrFail($id);

        $deletedData = [
            'name' => $detailUser->name ?? '-',
            'no_induk' => $detailUser->no_induk?? '-',
            'email' => $detailUser->detailUser->email ?? '-',
            'prodi' => $detailUser->prodi->name ?? '-',
            'level' => $detailUser->detailUser->level->nama_level ?? '-',
            'jenis_kelamin' => $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : ($detailUser->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
            'phone' => $detailUser->phone ?? '-',
        ];

        $user = $detailUser->detailUser;
        $detailUser->delete();
        if ($user) {
            $user->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail user berhasil dihapus.',
            'deleted_data' => $deletedData
        ]);
    }
}
