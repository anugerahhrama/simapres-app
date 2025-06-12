@empty($bobot)
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
    <form action="{{ route('spk.update', $bobot->id) }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
                <div class="modal-header text-dark bg-primary" style="border-radius: 8px 8px 0 0;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-edit mr-2"></i>Edit Bobot SPK
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Kesesuaian Keahlian</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $bobot->c1 }}" type="number" name="c1" id="c1" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-c1" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Pendaftaran</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $bobot->c2 }}" type="number" name="c2" id="c2" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-c2" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Biaya Pendaftaran</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $bobot->c3 }}" type="number" name="c3" id="c3" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-c3" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tingkatan Lomba</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $bobot->c4 }}" type="number" name="c4" id="c4" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-c4" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Nilai Benefit</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                            <input value="{{ $bobot->c5 }}" type="number" name="c5" id="c5" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-c5" class="error-text form-text text-danger"></small>
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
                    c1: {
                        required: true,
                        number: true
                    },
                    c2: {
                        required: true,
                        number: true
                    },
                    c3: {
                        required: true,
                        number: true
                    },
                    c4: {
                        required: true,
                        number: true
                    },
                    c5: {
                        required: true,
                        number: true
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
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
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
