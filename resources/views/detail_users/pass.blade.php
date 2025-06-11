<form action="{{ route('detailusers.pass.update', $detailUser->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <div class="modal-header bg-dark text-white" style="border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-key mr-2"></i>Ganti Password
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" name="password" id="password" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" 
                                placeholder="Masukkan password baru" required>
                        </div>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Konfirmasi Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" name="confirmpassword" id="confirmpassword" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" 
                                placeholder="Konfirmasi password baru" required>
                        </div>
                        <small id="error-confirmpassword" class="error-text form-text text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
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
