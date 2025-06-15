@empty($prestasi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-circle mr-2"></i>Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
                <h4 class="text-danger font-weight-bold">Data Tidak Ditemukan</h4>
                <p class="text-muted">Data prestasi tidak ditemukan dalam sistem</p>
                <a href="{{ route('prestasi.index') }}" class="btn btn-outline-danger btn-lg px-5">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 8px 8px 0 0;">
                <h5 class="modal-title"><i class="fas fa-info-circle mr-2"></i>Detail Prestasi</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-3 font-weight-bold">Informasi Dasar</h6>

                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-book mr-2"></i>Nama Lomba</span>
                            <span class="font-weight-bold text-primary">{{ $prestasi->nama_lomba }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-building mr-2"></i>Penyelenggara</span>
                            <span class="font-weight-bold text-dark">{{ $prestasi->penyelenggara ?? '-' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-layer-group mr-2"></i>Kategori / Tingkatan</span>
                            <span class="font-weight-bold text-dark">{{ $prestasi->kategori ?? '-' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-calendar-alt mr-2"></i>Tanggal</span>
                            <span class="font-weight-bold text-dark">
                                {{ \Carbon\Carbon::parse($prestasi->tanggal)->translatedFormat('d F Y') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-medal mr-2"></i>Pencapaian</span>
                            <span class="font-weight-bold text-dark">{{ $prestasi->pencapaian ?? '-' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-start pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-align-left mr-2"></i>Deskripsi</span>
                            <span class="text-dark text-right" style="max-width: 70%;">{{ $prestasi->deskripsi ?? '-' }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-start pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-comments mr-2"></i>Evaluasi Diri</span>
                            <span class="text-dark text-right" style="max-width: 70%;">{{ $prestasi->evaluasi_diri ?? '-' }}</span>
                        </div>

                        <h6 class="text-muted mb-3 mt-4 font-weight-bold">Bukti Prestasi</h6>
                        @if ($prestasi->bukti->isNotEmpty())
                            @foreach ($prestasi->bukti as $bukti)
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded mb-2">
                                    <span class="text-dark"><i class="fas fa-file-alt mr-2"></i>{{ $bukti->nama_file }}</span>
                                    <a href="{{ Storage::url($bukti->path_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Tidak ada file bukti yang diunggah.</p>
                        @endif

                        <h6 class="text-muted mb-3 mt-4 font-weight-bold">Status</h6>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-check-circle mr-2"></i>Status Verifikasi</span>
                            <span class="font-weight-bold text-dark">
                                @if ($prestasi->status_verifikasi == 'verified')
                                    <span class="badge badge-success">Terverifikasi</span>
                                @elseif($prestasi->status_verifikasi == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endempty
