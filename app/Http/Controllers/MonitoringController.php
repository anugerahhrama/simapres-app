<?php

namespace App\Http\Controllers;

use App\Models\BuktiPrestasi;
use App\Models\Lomba;
use App\Models\PendaftaranLombas;
use App\Models\Prestasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Dapatkan detail user yang login (yang berfungsi sebagai data mahasiswa)
        // Perhatikan relasi di User.php: public function detailUser(): HasOne { return $this->hasOne(DetailUser::class, 'user_id', 'id_bigint'); }
        $detailUser = $user->detailUser;

        // Jika user belum memiliki detail user, arahkan untuk melengkapi profil
        if (!$detailUser) {
            return redirect()->route('profile.edit')->with('error', 'Data profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu.');
        }

        // Data untuk kotak informasi di atas
        // "Lomba Aktif": Lomba yang periode pendaftarannya masih aktif
        $totalLombaAktif = Lomba::where('awal_registrasi', '<=', Carbon::now())
            ->where('akhir_registrasi', '>=', Carbon::now())
            ->count();

        // "Dokumen Tertunda": BuktiPrestasi dengan status 'Menunggu' yang diunggah oleh user ini
        // Asumsi BuktiPrestasi terkait ke Prestasi, dan Prestasi terkait ke User.
        $totalDokumenTertunda = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id)->where('status_verifikasi', 'Menunggu'); // Asumsi Prestasi memiliki user_id
        })->count();

        // "Sertifikat": BuktiPrestasi dengan jenis 'Sertifikat' dan status 'Disetujui' oleh user ini
        $totalSertifikat = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id)->where('status_verifikasi', 'Disetujui'); // Asumsi Prestasi memiliki user_id
        })
            ->where('jenis_dokumen', 'Sertifikat')
            ->count();

        // "Evaluasi": Jumlah total Prestasi yang dimiliki user ini
        $totalEvaluasi = Prestasi::where('mahasiswa_id', $user->id)->count(); // Asumsi Prestasi memiliki user_id


        // Lomba yang Diikuti
        $pendaftaranLombas = PendaftaranLombas::where('user_id', $user->id)
            ->with('lomba') // Pastikan relasi 'lomba' ada di PendaftaranLombas
            ->get();

        // Dokumen yang Diunggah (BuktiPrestasi terkait user)
        $dokumenDiunggah = BuktiPrestasi::whereHas('prestasi', function ($query) use ($user) {
            $query->where('mahasiswa_id', $user->id); // Asumsi Prestasi memiliki user_id
        })
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        // Log Aktivitas user yang login
        //$logAktivitas = LogAktivitas::where('user_id', $user->id)
        //                            ->orderBy('tanggal_aktivitas_date', 'desc')
        //                            ->limit(5)
        //                            ->get();

        return view('monitoring.index', compact(
            'user',
            'detailUser',
            'totalLombaAktif',
            'totalDokumenTertunda',
            'totalSertifikat',
            'totalEvaluasi',
            'pendaftaranLombas',
            'dokumenDiunggah',
        //     //'logAktivitas'
        ));

        //return response()->json([
        //    'user' => $user,
        //    'detailUser' => $detailUser,
        //    'totalLombaAktif' => $totalLombaAktif,
        //    'totalDokumenTertunda' => $totalDokumenTertunda,
        //    'totalSertifikat' => $totalSertifikat,
        //    'totalEvaluasi' => $totalEvaluasi,
        //    'pendaftaranLombas' => $pendaftaranLombas,
        //    'dokumenDiunggah' => $dokumenDiunggah,
        //    //'logAktivitas' => $logAktivitas
        //]);
    }
}
