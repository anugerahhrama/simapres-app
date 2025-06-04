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
            'title' => 'Daftar Pengguna',
            'list'  => ['Home', 'Pengguna']
        ];

        $prodis = ProgramStudi::all();
        $levels = Level::all();

        return view('detail_users.index', compact('breadcrumb', 'prodis', 'levels'));
    }

    public function list(Request $request)
    {
        $data = DetailUser::with(['user.level', 'prodi']); // Ganti detailUser.level jadi user.level

        if ($request->prodi_id) {
            $data->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return ($row->name && $row->name !== '') ? $row->name : '-';
            })
            ->addColumn('email', function ($row) {
                return $row->user->email ?? '-';
            })
            ->addColumn('level', function ($row) {
                return $row->user->level->nama_level ?? '-';
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
            $level = Level::find($request->level_id);
            $rules = [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirmpassword' => 'required|same:password',
                'no_induk' => 'required|string|max:20|unique:detail_users,no_induk',
                'name' => 'required|string|max:255',
                'level_id' => 'required|exists:levels,id',
                'phone' => 'nullable|string|max:20',
                'jenis_kelamin' => 'nullable|in:L,P',
            ];
            if ($level && $level->level_code === 'MHS') {
                $rules['prodi_id'] = 'required|exists:program_studis,id';
            }

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
                'jenis_kelamin' => $request->jenis_kelamin,
                'phone' => $request->phone,
                'prodi_id' => $level && $level->level_code === 'MHS' ? $request->prodi_id : null
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
        $detailUser = DetailUser::with(['user.level', 'prodi'])->findOrFail($id);
        return view('detail_users.show', compact('detailUser'));
    }

    /**
     * Display a listing of the resource.
     */
    public function edit(string $id)
    {
        $detailUser = DetailUser::with(['user.level'])->findOrFail($id);
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
            $detailUser = DetailUser::with(['user'])->findOrFail($id);
            $level = Level::find($request->level_id);

            $rules = [
                'email' => 'required|email|unique:users,email,' . $detailUser->user_id,
                'no_induk' => 'required|string|max:20|unique:detail_users,no_induk,' . $id,
                'name' => 'required|string|max:255',
                'level_id' => 'required|exists:levels,id',
                'phone' => 'nullable|string|max:20',
                'jenis_kelamin' => 'nullable|in:L,P',
                'password' => 'nullable|min:6',
                'confirmpassword' => 'nullable|same:password',
            ];
            if ($level && $level->level_code === 'MHS') {
                $rules['prodi_id'] = 'required|exists:program_studis,id';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $user = $detailUser->user;
            if ($user) {
                $user->update([
                    'email' => $request->email,
                    'level_id' => $request->level_id,
                ]);
                if ($request->password) {
                    $user->update([
                        'password' => bcrypt($request->password)
                    ]);
                }
            }

            $detailUser->update([
                'no_induk' => $request->no_induk,
                'name' => $request->name,
                'prodi_id' => $level && $level->level_code === 'MHS' ? $request->prodi_id : null,
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
        $detailUser = DetailUser::with(['user.level', 'prodi'])->findOrFail($id);
        $prodis = ProgramStudi::all();
        $user = User::all();

        return view('detail_users.confirm', compact('detailUser'));
    }

    /**
     * Display a listing of the resource.
     */
    public function destroy(string $id)
    {
        $detailUser = DetailUser::with(['user.level', 'prodi'])->findOrFail($id);

        $deletedData = [
            'name' => $detailUser->name ?? '-',
            'no_induk' => $detailUser->no_induk ?? '-',
            'email' => $detailUser->user->email ?? '-',
            'prodi' => $detailUser->prodi->name ?? '-',
            'level' => $detailUser->user->level->nama_level ?? '-',
            'jenis_kelamin' => $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : ($detailUser->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
            'phone' => $detailUser->phone ?? '-',
        ];

        $user = $detailUser->user;
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
