@empty($verifPrestasi)
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
                <a href="{{ route('verifPres.index') }}" class="btn btn-outline-danger btn-lg px-5">
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
                    <i class="fas fa-info-circle mr-2"></i>Detail Prestasi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Data Mahasiswa</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-hashtag mr-2"></i>NIM</span>
                                    <span class="font-weight-bold text-primary">{{ $verifPrestasi->user->detailUser->no_induk ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-user mr-2"></i>Nama</span>
                                    <span class="font-weight-bold text-primary">{{ $verifPrestasi->user->detailUser->name ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-graduation-cap mr-2"></i>Program Studi</span>
                                    <span class="font-weight-bold text-primary">
                                        @php
                                            $prodi = $verifPrestasi->user->detailUser->prodi;
                                            echo $prodi ? $prodi->name : '-';
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Prestasi Mahasiswa</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-trophy mr-2"></i>Nama Lomba</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->nama_lomba }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-building mr-2"></i>Penyelenggara</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->penyelenggara }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-tag mr-2"></i>Kategori</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->kategori }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-medal mr-2"></i>Pencapaian</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->pencapaian }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-calendar mr-2"></i>Tanggal</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->tanggal }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-file-alt mr-2"></i>Deskripsi</span>
                                    <span class="font-weight-bold text-dark">{{ $verifPrestasi->deskripsi }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-file mr-2"></i>Bukti Prestasi</span>
                                    <span class="font-weight-bold text-primary">
                                        @if ($buktiPrestasi->isNotEmpty())
                                            @foreach ($buktiPrestasi as $bukti)
                                                <a href="{{ Storage::url($bukti->path_file) }}" target="_blank"
                                                    class="text-primary d-block mb-1">
                                                    <i class="fas fa-download mr-1"></i>{{ $bukti->nama_file }}
                                                </a>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-check-circle mr-2"></i>Status
                                        Verifikasi</span>
                                    <span
                                        class="font-weight-bold text-dark badge badge-warning">{{ $verifPrestasi->status_verifikasi }}</span>
                                </div>

                                @if ($verifPrestasi->status_verifikasi == 'pending')
                                    <div class="mt-4">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-check-circle mr-2"></i>Verifikasi
                                                    Prestasi</h6>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('verifPres.updateStatus', $verifPrestasi->id) }}" method="POST" id="verifikasiForm">
                                                    @csrf
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="form-group">
                                                        <label for="status_verifikasi">Status Verifikasi</label>
                                                        <select class="form-control" id="status_verifikasi"
                                                            name="status_verifikasi" required>
                                                            <option value="">Pilih Status</option>
                                                            <option value="verified">Setujui</option>
                                                            <option value="rejected">Tolak</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="catatan">Catatan (Opsional)</label>
                                                        <textarea class="form-control" id="catatan" name="catatan" rows="3"
                                                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                                    </div>
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-check mr-2"></i>Konfirmasi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#verifikasiForm').validate({
                rules: {
                    status_verifikasi: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(function() {
                                    window.location.href = '{{ route('verifPres.index') }}';
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan!'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
