<form action="{{ route('periodes.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <input value="" type="text" name="tahun_ajaran" id="tahun_ajaran" class="form-control"
                        required>
                    <small id="error-tahun_ajaran" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <input value="" type="text" name="semester" id="semester" class="form-control" required>
                    <small id="error-semester" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input value="" type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                        required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input value="" type="date" name="tanggal_selesai" id="tanggal_selesai"
                        class="form-control" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

@push('js')
    <script>
        $(document).ready(function() {
                    $("#form-tambah").validate({
                            rules: {
                                tahun_ajaran: {
                                    required: true,
                                    maxlength: 4
                                },
                                semester: {
                                    required: true,
                                    minlength: 3,
                                    maxlength: 100,
                                },
                                tanggal_mulai: {
                                    required: true,
                                    date: true
                                },
                                tanggal_selesai: {
                                    required: true,
                                    date: true
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
@endpush
