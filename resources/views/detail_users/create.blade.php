<form action="{{ route('detailusers.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-regular fa-circle-plus mr-2"></i>Tambah Data Pengguna
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="form-group">
                    <label class="font-weight-bold">No Induk</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-id-card"></i>
                            </span>
                        </div>
                        <input type="text" name="no_induk" id="no_induk" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;" 
                            placeholder="Masukkan nomor induk" required>
                    </div>
                    <small id="error-no_induk" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nama Lengkap</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input type="text" name="name" id="name" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    <small id="error-name" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Program Studi</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                        </div>
                        <select name="prodi_id" id="prodi_id" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Level</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-layer-group"></i>
                            </span>
                        </div>
                        <select name="level_id" id="level_id" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->nama_level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Jenis Kelamin</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                        </div>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">No Telepon</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-phone"></i>
                            </span>
                        </div>
                        <input type="text" name="phone" id="phone" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;"
                            placeholder="Masukkan nomor telepon" required>
                    </div>
                    <small id="error-phone" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        <input type="email" name="email" id="email" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;" 
                            placeholder="Masukkan email" required>
                    </div>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" name="password" id="password" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;"
                            placeholder="Masukkan password" required>
                    </div>
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Konfirmasi Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control border-left-0" 
                            style="border: none; border-radius: 0 4px 4px 0 !important;"
                            placeholder="Konfirmasi password" required>
                    </div>
                    <small id="error-confirmpassword" class="error-text form-text text-danger"></small>
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
    $(document).ready(function () {
        $("#form-tambah").validate({
            rules: {
                no_induk: {
                    required: true,
                    maxlength: 20
                },
                name: {
                    required: true,
                    minlength: 3
                },
                prodi_id: {
                    required: true
                },
                level_id: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirmpassword: {
                    required: true,
                    equalTo: "#password"
                },
                phone: {
                    maxlength: 20
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataTable.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
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
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
