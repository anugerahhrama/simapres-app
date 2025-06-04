@empty($prestasi)
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
                <a href="{{ route('prestasi.index') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('prestasi.destroy', $prestasi->id) }}" method="POST" id="form-confirm-prestasi-modal"> 
        @csrf
        @method('DELETE') {{-- Jika ini untuk hapus, method DELETE diperlukan --}}
        
        <div class="modal-header"> {{-- Hapus ID modal-master jika tidak perlu di sini --}}
            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Prestasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda ingin menghapus data ini?
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">Judul Lomba:</th>
                    <td class="col-9">{{ $prestasi->judul_lomba }}</td> {{-- Sesuaikan dengan field prestasi --}}
                </tr>
                <tr>
                    <th class="text-right col-3">Penyelenggara:</th>
                    <td class="col-9">{{ $prestasi->penyelenggara }}</td> {{-- Sesuaikan dengan field prestasi --}}
                </tr>
                <tr>
                    <th class="text-right col-3">Kategori :</th>
                    <td class="col-9">{{ $prestasi->kategori }}</td> {{-- Sesuaikan dengan field prestasi --}}
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi:</th>
                    <td class="col-9">{{ $prestasi->deskripsi }}</td> {{-- Sesuaikan dengan field prestasi --}}
                </tr>
                <tr>
                    <th class="text-right col-3">Pencapaian:</th>
                    <td class="col-9">{{ $prestasi->pencapaian }}</td> {{-- Sesuaikan dengan field prestasi --}}
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <a href="{{ route('prestasi.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" >Ya, Hapus</button>
        </div>
    </form>
@endempty