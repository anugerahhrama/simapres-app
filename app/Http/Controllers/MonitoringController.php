<?php

namespace App\Http\Controllers;

use App\Models\BuktiPrestasi;
use App\Models\Prestasi;
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

        // Semua prestasi user
        $daftarPrestasi = Prestasi::where('mahasiswa_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Dokumen bukti prestasi yang diunggah
        $dokumenDiunggah = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->with('prestasi')
            ->orderBy('tanggal_upload', 'desc')
            ->get()
            ->map(function ($item) {
                $status = $item->prestasi->status_verifikasi ?? '-';
                $item->status_verifikasi = $status == 'verified' ? 'Disetujui' : ($status == 'rejected' ? 'Ditolak' : 'Menunggu');
                return $item;
            });

        // Sertifikat: semua dokumen bukti prestasi
        $sertifikatList = $dokumenDiunggah;

        // Statistik
        $totalLombaAktif = $daftarPrestasi->where('status_verifikasi', 'verified')->count();
        $totalSertifikat = $sertifikatList->count();
        $totalEvaluasi = $daftarPrestasi->count();

        return view('monitoring.index', compact(
            'user',
            'detailUser',
            'totalLombaAktif',
            'totalSertifikat',
            'totalEvaluasi',
            'daftarPrestasi',
            'dokumenDiunggah',
            'sertifikatList'
        ));
    }

    public function cetak()
    {
        $user = Auth::user();
        $detailUser = $user->detailUser;

        $daftarPrestasi = Prestasi::where('mahasiswa_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $dokumenDiunggah = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
                $query->where('mahasiswa_id', $user->id);
            })
            ->with('prestasi')
            ->orderBy('tanggal_upload', 'desc')
            ->get()
            ->map(function ($item) {
                $status = $item->prestasi->status_verifikasi ?? '-';
                $item->status_verifikasi = $status == 'verified' ? 'Disetujui' : ($status == 'rejected' ? 'Ditolak' : 'Menunggu');
                return $item;
            });

        $sertifikatList = $dokumenDiunggah;
        $totalLombaAktif = $daftarPrestasi->where('status_verifikasi', 'verified')->count();
        $totalSertifikat = $sertifikatList->count();
        $totalEvaluasi = $daftarPrestasi->count();

        $pdf = Pdf::loadView('monitoring.cetak', compact(
            'user',
            'detailUser',
            'totalLombaAktif',
            'totalSertifikat',
            'totalEvaluasi',
            'daftarPrestasi',
            'dokumenDiunggah',
            'sertifikatList'
        ));
        return $pdf->stream('monitoring-log-aktivitas.pdf');
    }
}
