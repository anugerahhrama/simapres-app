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
                    <input type="text" name="no_induk" value="{{ $detailUser->no_induk }}" class="form-control" required>
                    <small class="form-text text-danger error-text" id="error-no_induk"></small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $detailUser->nama_lengkap }}" class="form-control" required>
                    <small class="form-text text-danger error-text" id="error-nama_lengkap"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $detailUser->email }}" class="form-control" required>
                    <small class="form-text text-danger error-text" id="error-email"></small>
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
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

@push('js')
<script>
    $(document).ready(function () {
        $('#form-edit').validate({
            rules: {
                no_induk: { required: true },
                nama_lengkap: { required: true },
                email: { required: true, email: true },
                prodi_id: { required: true }
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
                            dataTable.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire('Gagal', response.message, 'error');
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
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush
