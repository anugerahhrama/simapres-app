<form action="{{ route('detailusers.update', $detailUser->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <div class="modal-header text-dark bg-warning" style="border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-edit mr-2"></i>Edit Detail User
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">No Induk</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-id-card"></i>
                                </span>
                            </div>
                            <input type="text" name="no_induk" id="no_induk" value="{{ $detailUser->no_induk }}" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                        </div>
                        <small id="error-no_induk" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lengkap</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text" name="name" id="name" value="{{ $detailUser->name }}" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                        </div>
                        <small id="error-name" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" name="email" value="{{ $detailUser->user->email }}" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                        </div>
                        <small class="form-text text-danger error-text" id="error-email"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Phone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>
                            <input type="text" name="phone" value="{{ $detailUser->phone }}" 
                                class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                        </div>
                        <small class="form-text text-danger error-text" id="error-phone"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Kelamin</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-venus-mars"></i>
                                </span>
                            </div>
                            <select name="jenis_kelamin" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ $detailUser->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $detailUser->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <small class="form-text text-danger error-text" id="error-jenis_kelamin"></small>
                    </div>
                </div>

                <div class="col-md-13">
                    <div class="form-group">
                        <label class="font-weight-bold">Level</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                            </div>
                            <select name="level_id" id="level_id" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;" required>
                                <option value="">-- Pilih Level --</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}" data-level-code="{{ $level->level_code }}">
                                        {{ $level->nama_level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small id="error-level_id" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="col-md-13" id="prodi-group">
                    <div class="form-group">
                        <label class="font-weight-bold">Program Studi</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                            </div>
                            <select name="prodi_id" id="prodi_id" class="form-control border-left-0" style="border: none ; border-radius: 0 4px 4px 0 !important;">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                    </div>
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
    $(document).ready(function () {
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
                    <input type="email" name="email" value="{{ $detailUser->user->email }}" class="form-control" required>
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
                    <label>Level</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}" {{ $detailUser->user->level_id == $level->id ? 'selected' : '' }}>
                                {{ $level->nama_level }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group" id="prodi-group">
                    <label>Program Studi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $detailUser->prodi_id == $prodi->id ? 'selected' : '' }}>{{ $prodi->name }}</option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="error-text form-text text-danger"></small>
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
    $(document).ready(function() {
        $('#form-edit').validate({
            rules: {
                no_induk: {
                    required: true,
                    maxlength: 50
                },
                nama_lengkap: {
                    required: true,
                    maxlength: 100
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    maxlength: 15
                },
                jenis_kelamin: {
                    required: true
                },
                prodi_id: {
                    required: true
                },
                level_id: {
                    required: true
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', response.message, 'success');
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            }
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
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
