@empty($bimbingan)
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
                <a href="{{ route('levels.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Bimbingan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID :</th>
                        <td class="col-9">{{ $bimbingan->id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mahasiswa :</th>
                        <td class="col-9">
                            {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->name : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIM :</th>
                        <td class="col-9">
                            {{ $bimbingan->mahasiswa && $bimbingan->mahasiswa->detailUser ? $bimbingan->mahasiswa->detailUser->no_induk : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Mulai :</th>
                        <td class="col-9">{{ $bimbingan->tanggal_mulai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Selesai :</th>
                        <td class="col-9">{{ $bimbingan->tanggal_selesai }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Status :</th>
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
                        <th class="text-right col-3">Catatan Bimbingan :</th>
                        <td class="col-9">{{ $bimbingan->catatan_bimbingan }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endempty
