@empty($level)
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
            <div class="modal-body">
                <div class="alert alert-danger border-0 shadow-sm">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ route('levels.index') }}" class="btn btn-warning btn-lg btn-block shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('levels.update', $level->id) }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
                <div class="modal-header text-dark bg-warning" style="border-radius: 8px 8px 0 0;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-edit mr-2"></i>Edit Data Level
                    </h5>
                    <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Level</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $level->level_code }}" type="text" name="level_code" id="level_code" 
                                class="form-control border-left-0"
                                style="border: none ; border-radius: 0 4px 4px 0 !important;"
                                placeholder="Masukkan kode level" required>
                        </div>
                        <small id="error-level_code" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Level</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                            </div>
                            <input value="{{ $level->nama_level }}" type="text" name="nama_level" id="nama_level" 
                                class="form-control border-left-0"
                                style="border: none ; border-radius: 0 4px 4px 0 !important;"
                                placeholder="Masukkan nama level" required>
                        </div>
                        <small id="error-nama_level" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning shadow-sm">
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
                    level_code: {
                        required: true,
                        maxlength: 10,
                        pattern: /^[A-Z]+$/
                    },
                    nama_level: {
                        required: true,
                        minlength: 3,
                        maxlength: 100,
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
                                dataLevel.ajax.reload();
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
@endempty
