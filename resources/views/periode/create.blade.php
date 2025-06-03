<form action="{{ route('periodes.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-regular fa-circle-plus mr-2"></i>Tambah Data Periode
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                    <div class="col-md-13">
                        <div class="form-group">
                            <label class="font-weight-bold">Tahun Ajaran</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input value="" type="text" name="tahun_ajaran" id="tahun_ajaran" 
                                    class="form-control" style="border: none; border-radius: 0 4px 4px 0 !important;"
                                    placeholder="Masukkan tahun ajaran" required>
                            </div>
                            <small id="error-tahun_ajaran" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-13">
                        <div class="form-group">
                            <label class="font-weight-bold">Semester</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-book"></i>
                                    </span>
                                </div>
                                <input value="" type="text" name="semester" id="semester"
                                    class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;"
                                    placeholder="Masukkan semester" required>
                            </div>
                            <small id="error-semester" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Mulai</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                <input value="" type="date" name="tanggal_mulai" id="tanggal_mulai"
                                    class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            </div>
                            <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Selesai</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                </div>
                                <input value="" type="date" name="tanggal_selesai" id="tanggal_selesai"
                                    class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            </div>
                            <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                        </div>
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
        $("#form-tambah").validate({
            rules: {
                tahun_ajaran: {
                    required: true,
                    maxlength: 50
                },
                semester: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 12
                },
                tanggal_mulai: {
                    required: true,
                    date: true
                },
                tanggal_selesai: {
                    required: true,
                    date: true
                },
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
                                showConfirmButton: false,
                                timer: 1500
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