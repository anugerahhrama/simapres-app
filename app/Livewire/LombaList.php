<?php

namespace App\Livewire;

use App\Models\Lomba;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LombaList extends Component
{
    use WithPagination;

    public $tingkatan_filter = '';
    public $search = '';
    public $tingkatanLomba;

    protected $paginationTheme = 'bootstrap';

    public function mount($tingkatanLomba)
    {
        $this->tingkatanLomba = $tingkatanLomba;
    }

    public function updating($field)
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->tingkatan_filter = '';
        $this->search = '';
    }

    public function render()
    {
        $user = Auth::user()->load('keahlian', 'preferensiTingkatLomba');

        $query = Lomba::with(['tingkatanLomba', 'keahlian'])
            ->where('status_verifikasi', 'verified');

        if ($this->tingkatan_filter) {
            $query->where('tingkatan_lomba_id', $this->tingkatan_filter);
        }

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        $lombas = $query->get()->map(function ($lomba) use ($user) {
            $lomba->skor = $this->hitungSpk($user, $lomba);
            return $lomba;
        })->sortByDesc('skor')->values();

        $page = request()->get('page', 1); // Ganti ini
        $perPage = 12;

        $paged = $lombas->forPage($page, $perPage);

        return view('livewire.lomba-list', [
            'lombas' => new \Illuminate\Pagination\LengthAwarePaginator(
                $paged,
                $lombas->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            )
        ]);
    }

    private function hitungSpk(User $user, Lomba $lomba): float
    {
        // 1. C1: Kecocokan Keahlian
        $userKeahlianIds = $user->keahlian->pluck('id')->toArray();
        $lombaKeahlianIds = $lomba->keahlian->pluck('id')->toArray();

        $cocok = array_intersect($userKeahlianIds, $lombaKeahlianIds);
        $c1 = count($cocok) > 0 ? 5 : 1;

        // 2. C2: Jenis Pendaftaran
        $c2 = $lomba->jenis_pendaftaran === 'gratis' ? 5 : 1;

        // 3. C3: Biaya Pendaftaran
        $harga = $lomba->harga_pendaftaran ?? 0;
        $c3 = match (true) {
            $harga <= 50000 => 5,
            $harga <= 150000 => 3,
            $harga <= 300000 => 2,
            $harga > 300000 => 1,
            default => 1,
        };

        // 4. C4: Preferensi Tingkatan Lomba
        $c4 = 1;
        if ($user->preferensiTingkatLomba) {
            $pref = $user->preferensiTingkatLomba;
            if ($lomba->tingkatan_lomba_id == $pref->pilihan_utama_id) {
                $c4 = 5;
            } elseif ($lomba->tingkatan_lomba_id == $pref->pilihan_kedua_id) {
                $c4 = 3;
            } elseif ($lomba->tingkatan_lomba_id == $pref->pilihan_ketiga_id) {
                $c4 = 1;
            }
        }

        // 5. C5: Benefit (Hadiah dan Sertifikat)
        $hadiah = $lomba->perkiraan_hadiah ?? 0;
        $benefit = $lomba->mendapatkan_sertifikat;
        $c5 = match (true) {
            $hadiah > 5000000 && $benefit => 5,
            $hadiah > 1000000 && $benefit => 4,
            $hadiah >= 500000 || $benefit => 3,
            $hadiah <= 500000 || $benefit => 2,
            default => 1,
        };

        $bobot = [
            'c1' => 0.25,
            'c2' => 0.10,
            'c3' => 0.10,
            'c4' => 0.20,
            'c5' => 0.35,
        ];

        return $c1 * $bobot['c1']
            + $c2 * $bobot['c2']
            + $c3 * $bobot['c3']
            + $c4 * $bobot['c4']
            + $c5 * $bobot['c5'];
    }
}
