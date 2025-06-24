<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Prestasi;
use App\Models\Lomba;
use App\Models\ProgramStudi;
use App\Models\Bimbingan;
use App\Models\PendaftaranLombas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        Carbon::setLocale('id');
        $user = Auth::user();
        $data = [];

        // Load relasi level
        $user = User::with('level')->find($user->id);

        switch ($user->level->level_code) {
            case 'ADM':
                $tahunSekarang = request('tahun', date('Y'));

                $tahunTersedia = collect(range(2020, 2025));

                $queryPrestasi = Prestasi::whereYear('tanggal', $tahunSekarang)
                    ->where('status_verifikasi', 'verified');

                $data = [
                    'totalUsers' => User::count(),
                    'totalProdis' => ProgramStudi::count(),
                    'totalLomba' => Lomba::count(),
                    'totalPrestasi' => Prestasi::count(),
                    'tahunTersedia' => $tahunTersedia,
                    'tahunSekarang' => $tahunSekarang,
                    'butuhVerifikasi' => Lomba::where('status_verifikasi', 'pending')
                        ->with(['createdBy.detailUser'])
                        ->latest()
                        ->get(),
                    'butuhVerifikasiPrestasi' => Prestasi::where('status_verifikasi', 'pending')
                        ->with(['user.detailUser'])
                        ->latest()
                        ->get(),
                    'upcomingEvents' => Lomba::where('status_verifikasi', 'verified')
                        ->where('awal_registrasi', '<=', now())
                        ->where('akhir_registrasi', '>=', now())
                        ->orderBy('awal_registrasi', 'asc')
                        ->take(5)
                        ->get(),
                    'prestasiTerbaru' => Prestasi::with(['user.detailUser'])
                        ->latest()
                        ->take(5)
                        ->get(),
                    'statistikPrestasi' => [
                        'bulanan' => $queryPrestasi->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
                            ->groupBy('bulan')
                            ->orderBy('bulan')
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'bulan' => (int)$item->bulan,
                                    'total' => (int)$item->total
                                ];
                            })
                            ->toArray(),
                        'tahunan' => Prestasi::selectRaw('YEAR(tanggal) as tahun, COUNT(*) as total')
                            ->where('status_verifikasi', 'verified')
                            ->whereYear('tanggal', '>=', 2020)
                            ->whereYear('tanggal', '<=', 2025)
                            ->groupBy('tahun')
                            ->orderBy('tahun')
                            ->get()
                            ->toArray()
                    ],
                ];
                break;

            case 'MHS':
                $data = [
                    'prestasiTerbaru' => Prestasi::where('mahasiswa_id', $user->id)
                        ->latest()
                        ->take(5)
                        ->get(),
                    'lombaTerdaftar' => PendaftaranLombas::with(['lomba', 'user.bimbingan'])
                        ->where('user_id', $user->id)
                        ->take(5)
                        ->get(),
                    'statistikPrestasi' => [
                        'bulanan' => [],
                        'tahunan' => []
                    ],
                    'tahunTersedia' => collect([]),
                    'tahunSekarang' => date('Y'),
                ];
                break;

            case 'DSN':
                $data = [
                    'totalBimbingan' => Bimbingan::where('dosen_id', $user->id)->count(),
                    'mahasiswaBimbingan' => Bimbingan::where('dosen_id', $user->id)
                        ->with(['mahasiswa.detailUser.prodi'])
                        ->latest()
                        ->get(),
                    'totalPendingVerification' => Lomba::where('status_verifikasi', 'pending')
                        ->where('created_by', $user->id)
                        ->count(),
                    'antrianVerifikasi' => Lomba::where('status_verifikasi', 'pending')
                        ->where('created_by', $user->id)
                        ->with(['createdBy.detailUser'])
                        ->latest()
                        ->take(5)
                        ->get(),
                    'statistikPrestasi' => [
                        'bulanan' => [],
                        'tahunan' => []
                    ],
                    'tahunTersedia' => collect([]),
                    'tahunSekarang' => date('Y'),
                ];
                break;
        }

        return view('index', $data);
    }
}
