<?php

namespace App\Livewire;

use App\Models\Lomba;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
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

        $lombas = $query->get()
            ->map(fn($lomba) => $lomba->forceFill(['skor' => $this->hitungSpk($user, $lomba)]))
            ->sortByDesc('skor')
            ->values();

        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $paged = $lombas->forPage($currentPage, $perPage);

        return view('livewire.lomba-list', [
            'lombas' => new LengthAwarePaginator(
                $paged,
                $lombas->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            )
        ]);
    }

    private function hitungSpk(User $user, Lomba $lomba): float
    {
        // C1: Kecocokan Keahlian
        $userKeahlianIds = $user->keahlian->pluck('id')->toArray();
        $lombaKeahlianIds = $lomba->keahlian->pluck('id')->toArray();
        $c1 = count(array_intersect($userKeahlianIds, $lombaKeahlianIds)) > 0 ? 3 : 1;

        // C2: Jenis Pendaftaran
        $c2 = $lomba->jenis_pendaftaran === 'tim' ? 3 : 1;

        // C3: Biaya Pendaftaran
        $harga = $lomba->harga_pendaftaran ?? 0;
        $c3 = $harga == 0 ? 3 : 1;


        // C4: Preferensi Tingkatan Lomba
        $c4 = 1;
        if ($pref = $user->preferensiTingkatLomba) {
            $c4 = match ($lomba->tingkatan_lomba_id) {
                $pref->pilihan_utama_id => 5,
                $pref->pilihan_kedua_id => 3,
                $pref->pilihan_ketiga_id => 1,
                default => 1,
            };
        }

        // C5: Benefit (Hadiah dan Sertifikat)
        $hadiah = $lomba->hadiah ?? []; // array: ['uang', 'trofi', 'sertifikat']
        $uang = in_array('uang', $hadiah);
        $trofi = in_array('trofi', $hadiah);
        $sertifikat = in_array('sertifikat', $hadiah);
        $c5 = match (true) {
            $uang && $trofi && $sertifikat => 5,
            $uang && $sertifikat && !$trofi => 4,
            $trofi && $sertifikat && !$uang => 3,
            ($uang xor $trofi) && !$sertifikat => 2,
            !$uang && !$trofi && $sertifikat => 1,
            default => 1,
        };

        // Bobot
        $bobot = [
            'c1' => 0.4,
            'c2' => 0.2,
            'c3' => 0.1,
            'c4' => 0.2,
            'c5' => 0.1,
        ];

        // WASPAS Calculation
        $wsm = $c1 * $bobot['c1'] + $c2 * $bobot['c2'] + $c3 * $bobot['c3'] + $c4 * $bobot['c4'] + $c5 * $bobot['c5'];
        $wpm = pow($c1, $bobot['c1']) * pow($c2, $bobot['c2']) * pow($c3, $bobot['c3']) * pow($c4, $bobot['c4']) * pow($c5, $bobot['c5']);
        $lambda = 0.5;

        return $lambda * $wsm + (1 - $lambda) * $wpm;
    }
}
