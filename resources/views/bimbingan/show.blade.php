@empty($bimbingan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i>Kesalahan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                    <h4 class="text-danger font-weight-bold">Data Tidak Ditemukan</h4>
                    <p class="text-muted">Data yang anda cari tidak ditemukan dalam sistem</p>
                </div>
                <a href="{{ route('bimbingan.index') }}" class="btn btn-outline-danger btn-lg px-5">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-info-circle mr-2"></i>Detail Data Bimbingan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-hashtag mr-2"></i>ID</span>
                            <span class="font-weight-bold text-primary">{{ $bimbingan->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-user mr-2"></i>Mahasiswa</span>
                            <span class="font-weight-bold text-dark">
                                {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->name : '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-id-card mr-2"></i>NIM</span>
                            <span class="font-weight-bold text-dark">
                                {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->no_induk : '-' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-calendar mr-2"></i>Tanggal Mulai</span>
                            <span class="font-weight-bold text-dark">{{ $bimbingan->tanggal_mulai }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-calendar-check mr-2"></i>Tanggal Selesai</span>
                            <span class="font-weight-bold text-dark">{{ $bimbingan->tanggal_selesai }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-info-circle mr-2"></i>Status</span>
                            <span class="font-weight-bold text-dark">
                                @php
                                    $statusText = '-';
                                    if ($bimbingan->status == 1) $statusText = 'Belum Mulai';
                                    elseif ($bimbingan->status == 2) $statusText = 'Berjalan';
                                    elseif ($bimbingan->status == 3) $statusText = 'Selesai';
                                @endphp
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-start pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-sticky-note mr-2"></i>Catatan Bimbingan</span>
                            <span class="font-weight-medum text-dark text-right" style="max-width: 70%;">{{ $bimbingan->catatan_bimbingan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endempty
