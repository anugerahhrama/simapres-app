<form action="{{ route('lomba.store') }}" method="POST" id="form-tambah-lomba">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Lomba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Judul Lomba</label>
                    <input type="text" name="judul_lomba" id="judul_lomba" class="form-control" required>
                    <small id="error-judul_lomba" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tingkatan</label>
                    <select name="tingkatan" id="tingkatan" class="form-control" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        <option value="pemula">Pemula</option>
                        <option value="lokal">Lokal</option>
                        <option value="regional">Regional</option>
                        <option value="nasional">Nasional</option>
                        <option value="internasional">Internasional</option>
                    </select>
                    <small id="error-tingkatan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Penyelenggara</label>
                    <input type="text" name="penyelenggara" id="penyelenggara" class="form-control">
                    <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-tambah-lomba").validate({
            rules: {
                judul_lomba: { required: true, minlength: 3 },
                kategori: { required: true },
                tingkatan: { required: true },
                penyelenggara: { maxlength: 100 },
                deskripsi: { maxlength: 255 }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataTable.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
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
