@empty($bimbingan)
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
                <a href="#" class="btn btn-warning btn-lg btn-block shadow-sm" data-dismiss="modal">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('bimbingan.update', $bimbingan->id) }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
                <div class="modal-header text-dark bg-warning" style="border-radius: 8px 8px 0 0;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-edit mr-2"></i>Edit Data Bimbingan
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
                            <select name="mahasiswa_id" class="form-control border-left-0" required>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->id }}" {{ $bimbingan->mahasiswa_id == $mhs->id ? 'selected' : '' }}>
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
                                    <input value="{{ $bimbingan->tanggal_mulai }}" type="date" name="tanggal_mulai" 
                                        class="form-control border-left-0" required>
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
                                    <input value="{{ $bimbingan->tanggal_selesai }}" type="date" name="tanggal_selesai" 
                                        class="form-control border-left-0">
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
                                    <i class="fas fa-tasks"></i>
                                </span>
                            </div>
                            <select name="status" class="form-control border-left-0" required>
                                <option value="1" {{ $bimbingan->status == 1 ? 'selected' : '' }}>Belum Mulai</option>
                                <option value="2" {{ $bimbingan->status == 2 ? 'selected' : '' }}>Berjalan</option>
                                <option value="3" {{ $bimbingan->status == 3 ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <small class="form-text text-muted">Keterangan: Belum Mulai, Berjalan, Selesai</small>
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
                            <textarea name="catatan_bimbingan" class="form-control border-left-0" rows="3" placeholder="Tambahkan catatan">{{ $bimbingan->catatan_bimbingan }}</textarea>
                        </div>
                        <small id="error-catatan_bimbingan" class="error-text form-text text-danger"></small>
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
                    mahasiswa_id: {
                        required: true,
                    },
                    tanggal_mulai: {
                        required: true,
                        date: true
                    },
                    tanggal_selesai: {
                        date: true
                    },
                    status: {
                        required: true,
                    },
                    catatan_bimbingan: {
                        maxlength: 255,
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
                                dataBimbingan.ajax.reload();
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
