<div class="modal-header"> {{-- Tambahkan header modal di sini --}}
    <h5 class="modal-title" id="editModalLabel">Edit Data Prestasi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

{{-- Pastikan ini adalah form AJAX, jadi aksi submit akan ditangani oleh JS --}}
<form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" id="form-edit-prestasi-modal">
    @csrf
    @method('PUT') {{-- Penting: Menggunakan metode PUT untuk update --}}

    <div class="modal-body"> 
        <input type="hidden" name="mahasiswa_id" value="{{ $prestasi->mahasiswa_id }}">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Mahasiswa</label>
            <div class="col-sm-10">
                <p class="form-control-static">
                    {{ $prestasi->user ? $prestasi->user->email : 'N/A' }}
                </p>
                <small class="text-danger error-text" id="error-mahasiswa_id"></small>
            </div>
        </div>

        {{-- Lomba (Dropdown) --}}
        <div class="form-group row">
            <label for="lomba_id" class="col-sm-2 col-form-label">Lomba</label>
            <div class="col-sm-10">
                <select class="form-control @error('lomba_id') is-invalid @enderror" id="lomba_id" name="lomba_id" required>
                    <option value="">- Pilih Lomba -</option>
                    @foreach($lombas as $lomba)
                        <option value="{{ $lomba->id }}" {{ (old('lomba_id', $prestasi->lomba_id) == $lomba->id) ? 'selected' : '' }}>
                            {{ $lomba->judul }} - {{ $lomba->penyelenggara }}
                        </option>
                    @endforeach
                </select>
                <small class="text-danger error-text" id="error-lomba_id"></small>
            </div>
        </div>

        {{-- Nama Kegiatan --}}
        <div class="form-group row">
            <label for="nama_kegiatan" class="col-sm-2 col-form-label">Nama Kegiatan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required>
                <small class="text-danger error-text" id="error-nama_kegiatan"></small>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="form-group row">
            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                <small class="text-danger error-text" id="error-deskripsi"></small>
            </div>
        </div>

        {{-- Tanggal --}}
        <div class="form-group row">
            <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($prestasi->tanggal)->format('Y-m-d')) }}" required>
                <small class="text-danger error-text" id="error-tanggal"></small>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="form-group row">
            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
                <select class="form-control" id="kategori" name="kategori" required>
                    <option value="Akademik" {{ (old('kategori', $prestasi->kategori) == 'Akademik') ? 'selected' : '' }}>Akademik</option>
                    <option value="Non Akademik" {{ (old('kategori', $prestasi->kategori) == 'Non Akademik') ? 'selected' : '' }}>Non Akademik</option>
                </select>
                <small class="text-danger error-text" id="error-kategori"></small>
            </div>
        </div>

        {{-- Pencapaian --}}
        <div class="form-group row">
            <label for="pencapaian" class="col-sm-2 col-form-label">Pencapaian</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pencapaian" name="pencapaian" value="{{ old('pencapaian', $prestasi->pencapaian) }}" required>
                <small class="text-danger error-text" id="error-pencapaian"></small>
            </div>
        </div>

        {{-- Evaluasi Diri --}}
        <div class="form-group row">
            <label for="evaluasi_diri" class="col-sm-2 col-form-label">Evaluasi Diri (Opsional)</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="evaluasi_diri" name="evaluasi_diri" rows="3">{{ old('evaluasi_diri', $prestasi->evaluasi_diri) }}</textarea>
                <small class="text-danger error-text" id="error-evaluasi_diri"></small>
            </div>
        </div>

        {{-- Status Verifikasi --}}
        <div class="form-group row">
            <label for="status_verifikasi" class="col-sm-2 col-form-label">Status Verifikasi</label>
            <div class="col-sm-10">
                <select class="form-control" id="status_verifikasi" name="status_verifikasi" required>
                    <option value="pending" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'pending') ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'approved') ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'rejected') ? 'selected' : '' }}>Rejected</option>
                </select>
                <small class="text-danger error-text" id="error-status_verifikasi"></small>
            </div>
        </div>
    </div> {{-- Penutup modal-body --}}

    <div class="modal-footer"> {{-- Tambahkan footer modal --}}
        <button type="submit" class="btn btn-primary btn-sm" >Simpan Perubahan</button>
        <button type="button" class="btn btn-secondary btn-sm">Batal</button>
    </div>
</form>

<script>
        $(document).ready(function() {
            $('#form-edit-prestasi-modal').validate({
                rules: {
                    lomba: {
                        required: true,
                        maxlength: 50
                    },
                    nama_kegiatan: {
                        required: true,
                        maxlength: 70
                    },
                    deskripsi: {
                        required: true,
                        maxlength: 255
                    },
                    tanggal: {
                        required: true,
                        date: true,
                    },
                    kategori: {
                        required: true,
                        maxlength: 50
                    },
                    pencapaian: {
                        required: true,
                        maxlength: 50
                    },
                    evaluasi_diri: {
                        maxlength: 255
                    },
                    
                },
                messages: { 
                    lomba_id: {
                        required: "Silakan pilih lomba."
                    },
                    nama_kegiatan: {
                        required: "Nama kegiatan wajib diisi.",
                        maxlength: "Nama kegiatan tidak boleh lebih dari 70 karakter."
                    },
                    deskripsi: {
                        required: "Deskripsi wajib diisi.",
                        maxlength: "Deskripsi tidak boleh lebih dari 255 karakter."
                    },
                    tanggal: {
                        required: "Tanggal wajib diisi.",
                        date: "Format tanggal tidak valid."
                    },
                    kategori: {
                        required: "Kategori wajib diisi.",
                        maxlength: "Kategori tidak boleh lebih dari 50 karakter."
                    },
                    pencapaian: {
                        required: "Pencapaian wajib diisi.",
                        maxlength: "Pencapaian tidak boleh lebih dari 50 karakter."
                    },
                    evaluasi_diri: {
                        maxlength: "Evaluasi diri tidak boleh lebih dari 255 karakter."
                }
            },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataPeriode.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
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