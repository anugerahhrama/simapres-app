@empty($bimbingan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="#" class="btn btn-warning" data-dismiss="modal">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('bimbingan.update', $bimbingan->id) }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Bimbingan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-control" required>
                            @foreach($mahasiswa as $mhs)
                                <option value="{{ $mhs->id }}" {{ $bimbingan->mahasiswa_id == $mhs->id ? 'selected' : '' }}>
                                    {{ $mhs->detailUser ? $mhs->detailUser->name : $mhs->name }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input value="{{ $bimbingan->tanggal_mulai }}" type="date" name="tanggal_mulai" class="form-control" required>
                        <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input value="{{ $bimbingan->tanggal_selesai }}" type="date" name="tanggal_selesai" class="form-control">
                        <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="1" {{ $bimbingan->status == 1 ? 'selected' : '' }}>1 - Belum Mulai</option>
                            <option value="2" {{ $bimbingan->status == 2 ? 'selected' : '' }}>2 - Berjalan</option>
                            <option value="3" {{ $bimbingan->status == 3 ? 'selected' : '' }}>3 - Selesai</option>
                        </select>
                        <small class="form-text text-muted">Keterangan: 1 = Belum Mulai, 2 = Berjalan, 3 = Selesai</small>
                        <small id="error-status" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Catatan Bimbingan</label>
                        <textarea name="catatan_bimbingan" class="form-control">{{ $bimbingan->catatan_bimbingan }}</textarea>
                        <small id="error-catatan_bimbingan" class="error-text form-text text-danger"></small>
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
