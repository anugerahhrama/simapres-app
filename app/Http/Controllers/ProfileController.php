<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->with('detailUser')->first();

        $breadcrumb = (object) [
            'title' => 'Profile User',
            'list'  => ['Home', 'Profile']
        ];

        return view('profile.index', compact('user', 'breadcrumb'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('id', $id)->with('detailUser')->first();

        $prodi = ProgramStudi::all();

        return view('profile.edit', compact('user', 'prodi'));
    }

    public function editPhoto(string $id)
    {
        $user = User::where('id', $id)->with('detailUser')->first();

        return view('profile.photo', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_induk' => 'required|string|max:20',
            'phone' => 'required|string|min:8|max:15',
            'jenis_kelamin' => 'required|in:L,P',
            'prodi' => 'required|exists:program_studis,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $user = User::findOrFail($id);
        $user->email = $request->email;
        $user->save();

        $detail = $user->detailUser;
        $detail->name = $request->name;
        $detail->no_induk = $request->no_induk;
        $detail->phone = $request->phone;
        $detail->jenis_kelamin = $request->jenis_kelamin;
        $detail->prodi_id = $request->prodi;
        $detail->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui.'
        ]);
    }

    public function updatePhoto(Request $request, string $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // max 2MB
        ]);

        $user = User::findOrFail($id);
        $detail = $user->detailUser;

        if ($detail->photo_file && Storage::exists('public/' . $detail->photo_file)) {
            Storage::delete('public/' . $detail->photo_file);
        }

        $photoPath = $request->file('photo')->store('uploads/profile', 'public');

        // Update path di database
        $detail->photo_file = $photoPath;
        $detail->save();

        return response()->json([
            'status' => true,
            'message' => 'Foto berhasil diperbarui.',
            'photo_url' => asset('storage/' . $photoPath)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
