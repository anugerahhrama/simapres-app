<form action="{{ route('bimbingan.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-regular fa-circle-plus mr-2"></i>Tambah Data Bimbingan
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group">
                    <label class="font-weight-bold">Mahasiswa</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user-graduate"></i>
                            </span>
                        </div>
                        <select name="mahasiswa_id" class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            @foreach($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}">
                                    {{ $mhs->detailUser ? $mhs->detailUser->name : $mhs->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
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
                                <input type="date" name="tanggal_mulai" class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" required>
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
                                <input type="date" name="tanggal_selesai" class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;">
                            </div>
                            <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="font-weight-bold">Status</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </div>
                        <select name="status" class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            <option value="1">Belum Mulai</option>
                            <option value="2">Berjalan</option>
                            <option value="3">Selesai</option>
                        </select>
                    </div>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group mt-3">
                    <label class="font-weight-bold">Catatan Bimbingan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-sticky-note"></i>
                            </span>
                        </div>
                        <textarea name="catatan_bimbingan" class="form-control border-left-0" style="border: none; border-radius: 0 4px 4px 0 !important;" placeholder="Tambahkan catatan"></textarea>
                    </div>
                    <small id="error-catatan_bimbingan" class="error-text form-text text-danger"></small>
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
                mahasiswa_id: { required: true },
                tanggal_mulai: { required: true, date: true },
                tanggal_selesai: { date: true },
                status: { required: true },
                catatan_bimbingan: { maxlength: 255 }
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
                            if (typeof dataBimbingan !== 'undefined') {
                                dataBimbingan.ajax.reload();
                            }
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
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); }
        });
    });
</script>
