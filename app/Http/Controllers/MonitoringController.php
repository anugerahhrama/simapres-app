<?php

namespace App\Http\Controllers;

use App\Models\BuktiPrestasi;
use App\Models\Lomba;
use App\Models\PendaftaranLombas;
use App\Models\Prestasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $detailUser = $user->detailUser;

        if (!$detailUser) {
            return redirect()->route('profile.edit')->with('error', 'Data profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu.');
        }

        $totalLombaAktif = Lomba::where('awal_registrasi', '<=', Carbon::now())
            ->where('akhir_registrasi', '>=', Carbon::now())
            ->count();

        $totalSertifikat = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id)
                ->where('status_verifikasi', 'Disetujui');
        })->where('jenis_dokumen', 'Sertifikat')->count();

        $totalEvaluasi = Prestasi::where('mahasiswa_id', $user->id)->count();

        $pendaftaranLombas = PendaftaranLombas::where('user_id', $user->id)
            ->with('lomba')
            ->get()
            ->map(function ($item) {
                $item->progress = 100;
                $item->label = $item->status == 'Selesai' ? 'Selesai' : ($item->status == 'Aktif' ? 'Aktif' : '-');
                return $item;
            });

        $dokumenDiunggah = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id);
        })
            ->with('prestasi')
            ->orderBy('tanggal_upload', 'desc')
            ->get()
            ->map(function ($item) {
                $item->status_verifikasi = $item->prestasi->status_verifikasi ?? '-';
                return $item;
            });

        return view('monitoring.index', compact(
            'user',
            'detailUser',
            'totalLombaAktif',
            'totalSertifikat',
            'totalEvaluasi',
            'pendaftaranLombas',
            'dokumenDiunggah'
        ));
    }

    public function cetak()
    {
        $user = Auth::user();
        $detailUser = $user->detailUser;

        $totalLombaAktif = Lomba::where('awal_registrasi', '<=', Carbon::now())
            ->where('akhir_registrasi', '>=', Carbon::now())
            ->count();

        $totalSertifikat = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id)
                ->where('status_verifikasi', 'Disetujui');
        })->where('jenis_dokumen', 'Sertifikat')->count();

        $totalEvaluasi = Prestasi::where('mahasiswa_id', $user->id)->count();

        $pendaftaranLombas = PendaftaranLombas::where('user_id', $user->id)
            ->with('lomba')
            ->get()
            ->map(function ($item) {
                $item->progress = 100;
                $item->label = $item->status == 'Selesai' ? 'Selesai' : ($item->status == 'Aktif' ? 'Aktif' : '-');
                return $item;
            });

        $dokumenDiunggah = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id);
        })
            ->with('prestasi')
            ->orderBy('tanggal_upload', 'desc')
            ->get()
            ->map(function ($item) {
                $item->status_verifikasi = $item->prestasi->status_verifikasi ?? '-';
                return $item;
            });

        $pdf = Pdf::loadView('monitoring.cetak', compact(
            'user',
            'detailUser',
            'totalLombaAktif',
            'totalSertifikat',
            'totalEvaluasi',
            'pendaftaranLombas',
            'dokumenDiunggah'
        ));
        return $pdf->stream('monitoring-log-aktivitas.pdf');
    }
}
