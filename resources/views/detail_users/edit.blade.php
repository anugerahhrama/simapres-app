<form action="{{ route('detailusers.update', $detailUser->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document" id="modal-master">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Detail User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>No Induk</label>
                    <input type="text" name="no_induk" id="no_induk" value="{{ $detailUser->no_induk }}" class="form-control" required>
                    <small id="error-no_induk" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ $detailUser->name }}" class="form-control" required>
                    <small id="error-name" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $detailUser->detailUser->email }}" class="form-control" required>
                    <small class="form-text text-danger error-text" id="error-email"></small>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ $detailUser->phone }}" class="form-control" required>
                    <small class="form-text text-danger error-text" id="error-phone"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ $detailUser->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $detailUser->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <small class="form-text text-danger error-text" id="error-jenis_kelamin"></small>
                </div>
                <div class="form-group">
                    <label>Prodi</label>
                    <select name="prodi_id" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $prodi->id == $detailUser->prodi_id ? 'selected' : '' }}>
                                {{ $prodi->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-danger error-text" id="error-prodi_id"></small>
                </div>
                 <div class="form-group">
                    <label>Level</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ $level->id == $detailUser->detailUser->level_id ? 'selected' : '' }}>
                                {{ $level->nama_level }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
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
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#form-edit').validate({
            rules: {
                no_induk: { required: true, maxlength: 50 },
                nama_lengkap: { required: true, maxlength: 100 },
                email: { required: true, email: true },
                phone: { required: true, maxlength: 15 },
                jenis_kelamin: { required: true },
                prodi_id: { required: true },
                level_id: { required: true },
                password: { required: true, minlength: 6 },
                confirmpassword: { required: true, equalTo: "#password" }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
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
