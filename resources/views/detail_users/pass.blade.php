<form action="{{ route('detailusers.pass.update', $detailUser->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required>
                    <small id="error-confirmpassword" class="error-text form-text text-danger"></small>
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
    $(document).ready(function() {
        $('#form-edit').validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                confirmpassword: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                password: {
                    required: "Password wajib diisi.",
                    minlength: "Password minimal 6 karakter."
                },
                confirmpassword: {
                    required: "Konfirmasi password wajib diisi.",
                    equalTo: "Konfirmasi password tidak cocok."
                }
            },
            errorElement: 'small',
            errorClass: 'form-text text-danger error-text',
            errorPlacement: function(error, element) {
                $('#error-' + element.attr('name')).text(error.text());
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
                return false;
            }
        });
    });
</script>
