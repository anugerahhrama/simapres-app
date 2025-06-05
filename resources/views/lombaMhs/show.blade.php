<div class="modal-header bg-primary text-white">
    <h5 class="modal-title" id="showLombaModalLabel">Detail Singkat Lomba</h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if($lomba)
    <div class="card shadow-sm border-0"> {{-- Card tanpa border bawaan, shadow kecil --}}
        <div class="card-body">
            <div class="card-item-category mb-1">{{ $lomba->kategori }}</div>
            <h5 class="card-item-title mb-1">{{ $lomba->judul }}</h5>
            <p class="card-item-detail font-weight-bold mb-3">Tingkat: {{ $lomba->tingkatanLomba->nama_tingkatan ?? 'N/A' }}</p>

            <p class="card-item-detail mb-1">
                <i class="fas fa-building mr-2"></i> Penyelenggara: {{ $lomba->penyelenggara }}
            </p>
            <p class="card-item-detail mb-3">
                <i class="fas fa-calendar-alt mr-2"></i> Tanggal: {{ \Carbon\Carbon::parse($lomba->awal_registrasi)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($lomba->akhir_registrasi)->translatedFormat('d F Y') }}
            </p>

            @if ($lomba->link_registrasi)
                <a href="{{ $lomba->link_registrasi }}" target="_blank" class="btn btn-primary btn-block">Daftar</a>
            @endif
        </div>
    </div>
    @else
        <div class="alert alert-danger text-center">Data lomba tidak ditemukan.</div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>