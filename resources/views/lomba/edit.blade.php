<form action="{{ route('lomba.update', $lomba->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Lomba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Judul Lomba</label>
                    <input type="text" name="judul_lomba" value="{{ $lomba->judul_lomba }}" class="form-control" required>
                    <small id="error-judul_lomba" class="form-text text-danger error-text"></small>
                </div>
                <div class="form-group">
                    <label>Tingkatan</label>
                    <select name="tingkatan" class="form-control" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        @foreach(['pemula', 'lokal', 'regional', 'nasional', 'internasional'] as $tingkat)
                            <option value="{{ $tingkat }}" {{ $lomba->tingkatan == $tingkat ? 'selected' : '' }}>
                                {{ ucfirst($tingkat) }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-tingkatan" class="form-text text-danger error-text"></small>
                </div>
                <div class="form-group">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" value="{{ $lomba->penyelenggara }}" class="form-control">
                    <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ $lomba->deskripsi }}</textarea>
                    <small id="error-deskripsi" class="form-text text-danger error-text"></small>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <select name="prodi_id" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $prodi->id == $lomba->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="form-text text-danger error-text"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#form-edit').validate({
            rules: {
                judul_lomba: { required: true, maxlength: 255 },
                kategori: { required: true },
                tingkatan: { required: true },
                penyelenggara: { maxlength: 100 },
                deskripsi: { maxlength: 255 },
                prodi_id: { required: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
