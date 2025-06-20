<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\PendaftaranLombas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PendaftaranLombasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $slug)
    {
        $lombaId = base64_decode($slug);
        $lomba = Lomba::findOrFail($lombaId);

        $breadcrumb = (object) [
            'title' => 'Pendaftaran lomba ' . $lomba->judul,
            'list'  => ['Home', 'Lomba']
        ];

        return view('lombaMhs.pendaftaran.create', compact('lomba', 'breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lomba = Lomba::findOrFail($request->lomba_id);
        $user = Auth::user()->id;

        try {
            DB::beginTransaction();

            $cek = PendaftaranLombas::where('user_id', $user)->where('lomba_id', $lomba->id)->exists();

            if (!$cek) {
                PendaftaranLombas::create([
                    'user_id' => $user,
                    'lomba_id' => $lomba->id,
                    'tanggal_daftar' => Carbon::now(),
                    'status' => 'pending',
                ]);
                DB::commit();
                $response = Http::head($lomba->link_registrasi); // cepat karena tidak ambil isi

                if ($response->successful()) {
                    return redirect($lomba->link_registrasi);
                } else {
                    return redirect()->back()->with('error', 'Link registrasi tidak tersedia atau tidak valid.');
                }
            } else {
                DB::rollBack();
                return redirect()->back()->withInput();
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PendaftaranLombas $pendaftaranLombas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PendaftaranLombas $pendaftaranLombas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PendaftaranLombas $pendaftaranLombas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PendaftaranLombas $pendaftaranLombas)
    {
        //
    }
}
