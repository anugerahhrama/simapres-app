{{-- resources/views/detail_users/show.blade.php --}}
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-4">No Induk :</th>
                    <td class="col-8">{{ $detailUser->no_induk ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Nama Lengkap :</th>
                    <td>{{ $detailUser->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Email :</th>
                    <td>{{ $detailUser->detailUser->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Program Studi :</th>
                    <td>{{ $detailUser->prodi->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Level :</th>
                    <td>{{ $detailUser->detailUser->level->nama_level ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Jenis Kelamin :</th>
                    <td>
                        {{ $detailUser->jenis_kelamin == 'L' ? 'Laki-laki' : ($detailUser->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right">No Telepon :</th>
                    <td>{{ $detailUser->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Tanggal Dibuat :</th>
                    <td>{{ $detailUser->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th class="text-right">Terakhir Diubah :</th>
                    <td>{{ $detailUser->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
