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
            <dl class="row">
                <dt class="col-sm-4">No Induk</dt>
                <dd class="col-sm-8">{{ $detailUser->no_induk ?? '-' }}</dd>

                <dt class="col-sm-4">Nama Lengkap</dt>
                <dd class="col-sm-8">{{ $detailUser->nama_lengkap ?? $detailUser->detailUser->id ?? '-' }}</dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">{{ $detailUser->email ?? $detailUser->detailUser->email ?? '-' }}</dd>

                <dt class="col-sm-4">Program Studi</dt>
                <dd class="col-sm-8">{{ $detailUser->prodi->name ?? '-' }}</dd>

                <dt class="col-sm-4">Tanggal Dibuat</dt>
                <dd class="col-sm-8">{{ $detailUser->created_at->format('d M Y H:i') }}</dd>

                <dt class="col-sm-4">Terakhir Diubah</dt>
                <dd class="col-sm-8">{{ $detailUser->updated_at->format('d M Y H:i') }}</dd>
            </dl>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
