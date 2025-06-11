@empty($lomba)
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
                <a href="{{ route('lomba.index') }}" class="btn btn-outline-danger btn-lg px-5">
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
                    <i class="fas fa-info-circle mr-2"></i>Detail Data Lomba
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <h6 class="text-muted mb-3 font-weight-bold">Informasi Dasar</h6>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-book mr-2"></i>Judul Lomba</span>
                            <span class="font-weight-bold text-primary">{{ $lomba->judul ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-building mr-2"></i>Penyelenggara</span>
                            <span class="font-weight-bold text-dark">{{ $lomba->penyelenggara ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-layer-group mr-2"></i>Tingkatan</span>
                            <span class="font-weight-bold text-dark">{{ $lomba->tingkatanLomba->nama ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-tag mr-2"></i>Kategori</span>
                            <span class="font-weight-bold text-dark">{{ $lomba->kategori ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-tools mr-2"></i>Bidang Keahlian</span>
                            <span class="font-weight-bold text-dark">
                                @if ($lomba->keahlian->isNotEmpty())
                                    {{ $lomba->keahlian->pluck('nama_keahlian')->join(', ') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-trophy mr-2"></i>Hadiah</span>
                            <span class="font-weight-bold text-dark">
                                @if ($lomba->hadiah)
                                    {{ implode(', ', $lomba->hadiah) }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-start pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-align-left mr-2"></i>Deskripsi</span>
                            <span class="font-weight-medum text-dark text-right" style="max-width: 70%;">{{ $lomba->deskripsi ?? '-' }}</span>
                        </div>

                        <h6 class="text-muted mb-3 mt-4 font-weight-bold">Informasi Pendaftaran</h6>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-calendar mr-2"></i>Awal Registrasi</span>
                            <span class="font-weight-bold text-dark">{{ $lomba->awal_registrasi ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-calendar-check mr-2"></i>Akhir Registrasi</span>
                            <span class="font-weight-bold text-dark">{{ $lomba->akhir_registrasi ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-users mr-2"></i>Jenis Pendaftaran</span>
                            <span class="font-weight-bold text-dark">{{ ucfirst($lomba->jenis_pendaftaran) ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                            <span class="text-muted"><i class="fas fa-money-bill-wave mr-2"></i>Biaya Pendaftaran</span>
                            <span class="font-weight-bold text-dark">
                                @if($lomba->harga_pendaftaran == 0)
                                    Gratis
                                @else
                                    Rp {{ number_format($lomba->harga_pendaftaran, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-link mr-2"></i>Link Registrasi</span>
                            <span class="font-weight-bold text-dark">
                                <a href="{{ $lomba->link_registrasi }}" target="_blank" class="text-primary">
                                    {{ $lomba->link_registrasi ?? '-' }}
                                </a>
                            </span>
                        </div>

                        <h6 class="text-muted mb-3 mt-4 font-weight-bold">Status</h6>
                        <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                            <span class="text-muted"><i class="fas fa-check-circle mr-2"></i>Status Verifikasi</span>
                            <span class="font-weight-bold text-dark">
                                @if ($lomba->status_verifikasi == 'verified')
                                    <span class="badge badge-success">Terverifikasi</span>
                                @elseif($lomba->status_verifikasi == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-secondary">Belum Terverifikasi</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endempty
