@empty($user)
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
                <a href="{{ route('profile.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('profile.update', $user->id) }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input value="{{ $user->detailUser->name }}" type="text" name="name" id="name" class="form-control" required>
                        <small id="error-name" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="{{ $user->email }}" type="text" name="email" id="email" class="form-control" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No Induk</label>
                        <input value="{{ $user->detailUser->no_induk }}" type="text" name="no_induk" id="no_induk" class="form-control" required>
                        <small id="error-no_induk" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input value="{{ $user->detailUser->phone }}" type="text" name="phone" id="phone" class="form-control" required>
                        <small id="error-phone" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="exampleRadios1" value="L" {{ $user->detailUser->jenis_kelamin === 'L' ? 'checked' : '' }}>
                            <label class="form-check-label" for="exampleRadios1">
                                Laki-Laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="exampleRadios2" value="P" {{ $user->detailUser->jenis_kelamin === 'P' ? 'checked' : '' }}>
                            <label class="form-check-label" for="exampleRadios2">
                                Perempuan
                            </label>
                        </div>
                        <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select class="custom-select" name="prodi">
                            <option selected disabled>Open this select menu</option>
                            @foreach ($prodi as $item)
                                <option value="{{ $item->id }}" {{ $user->detailUser->prodi_id === $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <small id="error-phone" class="error-text form-text text-danger"></small>
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
