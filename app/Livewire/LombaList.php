<?php

namespace App\Livewire;

use App\Models\Lomba;
use App\Models\SpkBobot;
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
        /** @var User $user */
        $user = Auth::user()->load('keahlian', 'preferensiTingkatLomba');

        $query = Lomba::with(['tingkatanLomba', 'keahlian'])
            ->where('status_verifikasi', 'verified');

        if ($this->tingkatan_filter) {
            $query->where('tingkatan_lomba_id', $this->tingkatan_filter);
        }

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        // 1. Get all relevant competitions based on filters
        $lombas = $query->get();

        // If no competitions, return early to avoid division by zero or errors
        if ($lombas->isEmpty()) {
            return view('livewire.lomba-list', [
                'lombas' => new LengthAwarePaginator(
                    collect(), // Empty collection for paged items
                    0,          // Total count
                    12,         // Per page
                    1,          // Current page
                    ['path' => request()->url(), 'query' => request()->query()]
                )
            ]);
        }

        // Get user's bobot (weights) once
        $bobot = SpkBobot::where('user_id', $user->id)->first();
        $userBobot = [
            'c1' => $bobot->c1 ?? 0,
            'c2' => $bobot->c2 ?? 0,
            'c3' => $bobot->c3 ?? 0,
            'c4' => $bobot->c4 ?? 0,
            'c5' => $bobot->c5 ?? 0,
        ];

        // 2. Calculate raw C values for all competitions to find maximums
        // We'll store these raw values temporarily to avoid re-calculation
        $lombasWithRawC = $lombas->map(function ($lombaItem) use ($user) {
            $rawC = $this->getRawCValues($user, $lombaItem); // Using a modified helper to just get C values
            $lombaItem->raw_c_values = $rawC; // Store raw C values on the object
            return $lombaItem;
        });

        // 3. Find the maximum value for each criterion (C1-C5) across ALL current lombas
        $maxValues = [
            'c1' => 0,
            'c2' => 0,
            'c3' => 0,
            'c4' => 0,
            'c5' => 0
        ];

        foreach ($lombasWithRawC as $lombaItem) {
            foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
                $maxValues[$c] = max($maxValues[$c], $lombaItem->raw_c_values[$c]);
            }
        }

        // 4. Calculate WASPAS score for each competition using normalized values
        $lombasWithScores = $lombasWithRawC->map(function ($lombaItem) use ($userBobot, $maxValues) {
            $norm = [];
            foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
                // Normalisasi: Raw C value divided by its maximum, ensuring no division by zero
                $norm[$c] = $lombaItem->raw_c_values[$c] / max($maxValues[$c], 1);
            }

            // Hitung WSM & WPM
            $wsm = 0;
            $wpm = 1;
            foreach (['c1', 'c2', 'c3', 'c4', 'c5'] as $c) {
                $wsm += $norm[$c] * $userBobot[$c];
                $wpm *= pow($norm[$c], $userBobot[$c]);
            }

            // WASPAS Calculation
            $waspas = 0.5 * $wsm + 0.5 * $wpm;

            $lombaItem->skor = $waspas;
            return $lombaItem;
        });

        // 5. Sort the competitions by their calculated 'skor' in descending order
        $sortedLombas = $lombasWithScores->sortByDesc('skor')->values();

        // 6. Implement pagination
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $paged = $sortedLombas->forPage($currentPage, $perPage);

        return view('livewire.lomba-list', [
            'lombas' => new LengthAwarePaginator(
                $paged,
                $sortedLombas->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            )
        ]);
    }

    /**
     * Helper to get raw C values for a specific Lomba and User.
     * This function is now focused solely on getting the raw scores,
     * not calculating WASPAS or fetching bobot.
     *
     * @param User $user The authenticated user.
     * @param Lomba $lomba The competition to evaluate.
     * @return array An associative array of raw C1-C5 values.
     */
    private function getRawCValues(User $user, Lomba $lomba): array
    {
        // C1: Kecocokan Keahlian
        $userKeahlianIds = $user->keahlian2->pluck('id')->toArray();
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
        $hadiah = $lomba->hadiah ?? '';

        if (is_string($hadiah) && str_starts_with($hadiah, '[')) {
            $hadiah = json_decode($hadiah, true);
        } elseif (is_string($hadiah)) {
            $hadiah = array_map('trim', explode(',', $hadiah));
        }
        $hadiah = is_array($hadiah) ? $hadiah : [];

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

        return compact('c1', 'c2', 'c3', 'c4', 'c5');
    }
}
