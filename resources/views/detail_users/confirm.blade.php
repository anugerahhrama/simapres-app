<div class="modal-dialog" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Hapus</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus data pengguna berikut?</p>
            <ul>
                <li><strong>No Induk:</strong> {{ $detailUser->no_induk }}</li>
                <li><strong>Nama:</strong> {{ $detailUser->detailUser->name ?? '-' }}</li>
                <li><strong>Email:</strong> {{ $detailUser->detailUser->email ?? '-' }}</li>
                <li><strong>Program Studi:</strong> {{ $detailUser->prodi->name ?? '-' }}</li>
            </ul>
        </div>

        <div class="modal-footer">
            <form action="{{ route('detailusers.destroy', $detailUser->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </form>
        </div>

    </div>
</div>
