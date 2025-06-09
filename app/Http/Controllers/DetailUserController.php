<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $totalUsers = User::count();
        $totalAdmin = User::where('level_id', 1)->count();
        $totalDosen = User::where('level_id', 2)->count();
        $totalMahasiswa = User::where('level_id', 3)->count();

        return view('detail_users.index', compact('breadcrumb', 'prodis', 'levels', 'totalUsers', 'totalAdmin', 'totalMahasiswa', 'totalDosen'));
    }

    public function list(Request $request)
    {
        $data = DetailUser::with(['user', 'prodi']); // Ganti detailUser.level jadi user.level

        if ($request->prodi_id) {
            $data->where('prodi_id', $request->prodi_id);
        }

        if ($request->level_id) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('level_id', $request->level_id);
            });
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
                $btn = '<div style="display: flex; justify-content: center; align-items: center; gap: 4px;">';

                $btn .= '<button type="button" class="btn btn-info btn-sm"
            style="padding: 4px 8px; font-size: 12px;"
            data-toggle="tooltip" data-placement="top" title="Lihat Detail"
            onclick="modalAction(\'' . route('detailusers.show', $row->id) . '\')">
            <i class="fas fa-eye"></i>
        </button>';

                $btn .= '<button type="button" class="btn btn-warning btn-sm"
            style="padding: 4px 8px; font-size: 12px;"
            data-toggle="tooltip" data-placement="top" title="Edit Data"
            onclick="modalAction(\'' . route('detailusers.edit', $row->id) . '\')">
            <i class="fas fa-edit"></i>
        </button>';

                $btn .= '<button type="button" class="btn btn-danger btn-sm"
            style="padding: 4px 8px; font-size: 12px;"
            data-toggle="tooltip" data-placement="top" title="Hapus Data"
            onclick="modalAction(\'' . route('detailusers.confirm', $row->id) . '\')">
            <i class="fas fa-trash"></i>
        </button>';

                $btn .= '<button type="button" class="btn btn-secondary btn-sm"
            style="padding: 4px 8px; font-size: 12px;"
            data-toggle="tooltip" data-placement="top" title="Ganti Password"
            onclick="modalAction(\'' . route('detailusers.pass', $row->id) . '\')">
            <i class="fas fa-key"></i>
        </button>';

                $btn .= '</div>';

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
        $detailUser = DetailUser::with(['user'])->findOrFail($id);
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

    public function changePass(string $id)
    {
        $detailUser = DetailUser::with(['user'])->findOrFail($id);

        return view('detail_users.pass', compact('detailUser'));
    }

    public function changePassUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:6'],
            'confirmpassword' => ['required', 'same:password']
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'confirmpassword.required' => 'Konfirmasi password wajib diisi.',
            'confirmpassword.same' => 'Konfirmasi password tidak cocok.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $detailUser = DetailUser::findOrFail($id);
        $user = $detailUser->user;

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diperbarui.'
        ]);
    }
}
