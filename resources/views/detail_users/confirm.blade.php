@empty($detailUser)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ route('detailusers.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('detailusers.destroy', $detailUser->id) }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data seperti di bawah ini?
                    </div>

                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">No Induk:</th>
                            <td class="col-9">{{ $detailUser->no_induk }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Lengkap:</th>
                            <td class="col-9">{{ $detailUser->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Email:</th>
                            <td class="col-9">{{ $detailUser->user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Program Studi:</th>
                            <td class="col-9">{{ $detailUser->prodi->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Level:</th>
                            <td class="col-9">{{ $detailUser->user->level->nama_level }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jenis Kelamin:</th>
                            <td class="col-9">
                                {{ $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : ($detailUser->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">No Telepon:</th>
                            <td class="col-9">{{ $detailUser->phone ?? '-' }}</td>
                        </tr>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-delete").validate({
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
                                dataTable.ajax.reload();
                            } else {
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
