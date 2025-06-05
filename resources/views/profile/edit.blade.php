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
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    no_induk: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    phone: {
                        required: true,
                        minlength: 8,
                        maxlength: 15,
                        digits: true
                    },
                    jenis_kelamin: {
                        required: true
                    },
                    prodi: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Nama wajib diisi.",
                        minlength: "Nama minimal 3 karakter.",
                        maxlength: "Nama maksimal 100 karakter."
                    },
                    email: {
                        required: "Email wajib diisi.",
                        email: "Format email tidak valid."
                    },
                    no_induk: {
                        required: "No Induk wajib diisi.",
                        minlength: "No Induk minimal 3 karakter.",
                        maxlength: "No Induk maksimal 20 karakter."
                    },
                    phone: {
                        required: "Nomor telepon wajib diisi.",
                        minlength: "Nomor telepon terlalu pendek.",
                        maxlength: "Nomor telepon terlalu panjang.",
                        digits: "Hanya angka yang diperbolehkan."
                    },
                    jenis_kelamin: {
                        required: "Silakan pilih jenis kelamin."
                    },
                    prodi: {
                        required: "Silakan pilih program studi."
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
                                }).then(() => {
                                    location.reload();
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
                errorElement: 'small',
                errorClass: 'form-text text-danger error-text',
                errorPlacement: function(error, element) {
                    if (element.attr("type") === "radio") {
                        element.closest('.form-group').append(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
