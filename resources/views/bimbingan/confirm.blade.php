{{-- filepath: resources/views/bimbingan/confirm.blade.php --}}
@empty($bimbingan)
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
                <a href="#" class="btn btn-warning" data-dismiss="modal">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('bimbingan.destroy', $bimbingan->id) }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Bimbingan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin menghapus data bimbingan berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Mahasiswa:</th>
                            <td class="col-9">
                                {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->name : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Mulai:</th>
                            <td class="col-9">{{ $bimbingan->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Status:</th>
                            <td class="col-9">
                                @php
                                    $statusText = '-';
                                    if ($bimbingan->status == 1) $statusText = 'Belum Mulai';
                                    elseif ($bimbingan->status == 2) $statusText = 'Berjalan';
                                    elseif ($bimbingan->status == 3) $statusText = 'Selesai';
                                @endphp
                                {{ $statusText }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Catatan:</th>
                            <td class="col-9">{{ $bimbingan->catatan_bimbingan ?? '-' }}</td>
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
            $("#form-delete").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: this.action,
                    type: this.method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            if (typeof dataBimbingan !== 'undefined') {
                                dataBimbingan.ajax.reload();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menghapus data.'
                        });
                    }
                });
            });
        });
    </script>
@endempty
