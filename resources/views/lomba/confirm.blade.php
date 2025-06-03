{{-- resources/views/lomba/confirm.blade.php --}}
@empty($lomba)
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
                <a href="{{ route('lomba.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('lomba.destroy', $lomba->id) }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Lomba</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                        Apakah Anda yakin ingin menghapus data lomba berikut?
                    </div>

                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Judul Lomba:</th>
                            <td class="col-9">{{ $lomba->judul_lomba ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tingkatan:</th>
                            <td class="col-9">{{ ucfirst($lomba->tingkatan) ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penyelenggara:</th>
                            <td class="col-9">{{ $lomba->penyelenggara ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi:</th>
                            <td class="col-9">{{ $lomba->deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Program Studi:</th>
                            <td class="col-9">{{ $lomba->prodi->name ?? '-' }}</td>
                        </tr>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
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
                                if (typeof dataTable !== 'undefined') {
                                    dataTable.ajax.reload();
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server.'
                            });
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
