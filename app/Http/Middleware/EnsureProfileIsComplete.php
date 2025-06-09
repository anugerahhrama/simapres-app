<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }


        if ($user->level->level_code === 'MHS') {

            $detail = $user->detailUser;
            $keahlian = $user->keahlian()->exists();
            $tingkatan = $user->preferensiTingkatLomba;

            $isComplete =
                $detail &&
                !empty($detail->name) &&
                !empty($detail->no_induk) &&
                !empty($detail->phone) &&
                !empty($detail->jenis_kelamin) &&
                !empty($detail->prodi_id) &&
                $keahlian &&
                $tingkatan;


            if (!$isComplete && !$request->is('*profile*')) {
                return redirect()->route('profile.index')->with('warning', 'Lengkapi profil terlebih dahulu sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}
