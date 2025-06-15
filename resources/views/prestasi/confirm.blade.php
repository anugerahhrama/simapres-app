@empty($prestasi)
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
                <a href="{{ route('prestasi.index') }}" class="btn btn-warning btn-lg btn-block shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
                <div class="modal-header text-dark bg-danger" style="border-radius: 8px 8px 0 0;">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fas fa-trash-alt mr-2"></i>Hapus Data Prestasi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm">
                        <h5><i class="icon fas fa-exclamation-triangle mr-2"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data seperti di bawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped shadow-sm">
                        <tr>
                            <th class="text-right col-3">Nama Lomba:</th>
                            <td class="col-9">{{ $prestasi->nama_lomba ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Kategori:</th>
                            <td class="col-9">{{ $prestasi->kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Penyelenggara:</th>
                            <td class="col-9">{{ $prestasi->penyelenggara ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi:</th>
                            <td class="col-9">{{ $prestasi->deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Pencapaian:</th>
                            <td class="col-9">{{ $prestasi->pencapaian ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger shadow-sm">
                        <i class="fas fa-trash-alt mr-2"></i>Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
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
