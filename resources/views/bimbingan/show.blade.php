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
                    <p class="text-muted">Data bimbingan yang anda cari tidak ditemukan dalam sistem</p>
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
                        {{-- Data Mahasiswa --}}
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Data Mahasiswa</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-hashtag mr-2"></i>NIM</span>
                                    <span class="font-weight-bold text-primary">
                                        {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->no_induk : '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-user mr-2"></i>Nama</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->name : '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-graduation-cap mr-2"></i>Program Studi</span>
                                    <span class="font-weight-bold text-dark">
                                        @php
                                            $prodi =
                                                $bimbingan->mahasiswa &&
                                                $bimbingan->mahasiswa->detailUser &&
                                                $bimbingan->mahasiswa->detailUser->prodi
                                                    ? $bimbingan->mahasiswa->detailUser->prodi->name
                                                    : '-';
                                            echo $prodi;
                                        @endphp
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Data Dosen Pembimbing --}}
                        @if (auth()->user() && isset(auth()->user()->level) && auth()->user()->level->level_code == 'ADM')
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user-tie mr-2"></i>Data Dosen Pembimbing</h6>
                                </div>
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                        <span class="text-muted"><i class="fas fa-hashtag mr-2"></i>NIP</span>
                                        <span class="font-weight-bold text-primary">
                                            {{ $bimbingan->dosen && $bimbingan->dosen->detailUser ? $bimbingan->dosen->detailUser->no_induk : '-' }}
                                        </span>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                        <span class="text-muted"><i class="fas fa-user mr-2"></i>Nama</span>
                                        <span class="font-weight-bold text-dark">
                                            {{ $bimbingan->dosen && $bimbingan->dosen->detailUser ? $bimbingan->dosen->detailUser->name : '-' }}
                                        </span>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                        <span class="text-muted"><i class="fas fa-graduation-cap mr-2"></i>Program
                                            Studi</span>
                                        <span class="font-weight-bold text-dark">
                                            @php
                                                $prodiDosen =
                                                    $bimbingan->dosen &&
                                                    $bimbingan->dosen->detailUser &&
                                                    $bimbingan->dosen->detailUser->prodi
                                                        ? $bimbingan->dosen->detailUser->prodi->name
                                                        : '-';
                                                echo $prodiDosen;
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Data Lomba --}}
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-trophy mr-2"></i>Data Lomba</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-trophy mr-2"></i>Nama Lomba</span>
                                    <span class="font-weight-bold text-dark">{{ $bimbingan->lomba->judul ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-building mr-2"></i>Penyelenggara</span>
                                    <span
                                        class="font-weight-bold text-dark">{{ $bimbingan->lomba->penyelenggara ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted">
                                        <i class="fas fa-calendar-alt mr-2"></i>Tanggal Mulai - Tanggal Selesai
                                    </span>
                                    <span class="font-weight-bold text-dark">
                                        @php
                                            \Carbon\Carbon::setLocale('id');
                                            $tanggalMulai = $bimbingan->lomba->awal_registrasi
                                                ? \Carbon\Carbon::parse(
                                                    $bimbingan->lomba->awal_registrasi,
                                                )->translatedFormat('d F Y')
                                                : '-';
                                            $tanggalSelesai = $bimbingan->lomba->akhir_registrasi
                                                ? \Carbon\Carbon::parse(
                                                    $bimbingan->lomba->akhir_registrasi,
                                                )->translatedFormat('d F Y')
                                                : '-';
                                        @endphp
                                        {{ $tanggalMulai }} - {{ $tanggalSelesai }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-start pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-sticky-note mr-2"></i>Deskripsi Lomba</span>
                                    <span class="font-weight-medium text-dark text-right"
                                        style="max-width: 70%;">{{ $bimbingan->lomba->deskripsi ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Data Bimbingan --}}
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Data Bimbingan</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-calendar mr-2"></i>Tanggal Mulai</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $bimbingan->tanggal_mulai ? \Carbon\Carbon::parse($bimbingan->tanggal_mulai)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pb-2 bg-white p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-calendar-check mr-2"></i>Tanggal
                                        Selesai</span>
                                    <span class="font-weight-bold text-dark">
                                        {{ $bimbingan->tanggal_selesai ? \Carbon\Carbon::parse($bimbingan->tanggal_selesai)->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-start pb-2 bg-light p-3 rounded">
                                    <span class="text-muted"><i class="fas fa-sticky-note mr-2"></i>Catatan
                                        Bimbingan</span>
                                    <span class="font-weight-medium text-dark text-right"
                                        style="max-width: 70%;">{{ $bimbingan->catatan_bimbingan ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Verifikasi --}}
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Bimbingan</h6>
                            </div>
                            <div class="card-body">
                                <form id="updateStatusForm" method="POST"
                                    action="{{ route('bimbingan.updateStatus', $bimbingan->id) }}">
                                    @csrf
                                    <div
                                        class="d-flex justify-content-between align-items-center pb-2 bg-light p-3 rounded">
                                        <span class="text-muted"><i class="fas fa-info-circle mr-2"></i>Status</span>
                                        <select name="status" class="form-control w-auto">
                                            <option value="0" {{ $bimbingan->status == 0 ? 'selected' : '' }}>Belum
                                                Mulai</option>
                                            <option value="1" {{ $bimbingan->status == 1 ? 'selected' : '' }}>
                                                Berjalan</option>
                                            <option value="2" {{ $bimbingan->status == 2 ? 'selected' : '' }}>Selesai
                                            </option>
                                        </select>
                                        <button type="submit" class="btn btn-primary ml-2">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#updateStatusForm').validate({
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
                                    window.location.href =
                                        '{{ route('bimbingan.index') }}';
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan!'
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
