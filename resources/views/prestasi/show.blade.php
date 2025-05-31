<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Prestasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if($prestasi)
                <table class="table table-bordered table-striped">
                    <tr>
                        <th style="width: 200px;">Mahasiswa</th>
                        <td>{{ $prestasi->mahasiswa_id ?? 'N/A' }}</td> {{-- Sesuaikan dengan nama relasi atau kolom di model Prestasi --}}
                    </tr>
                    <tr>
                        <th>Judul Lomba</th>
                        <td>{{ $prestasi->judul_lomba }}</td>
                    </tr>
                    <tr>
                        <th>Penyelenggara</th>
                        <td>{{ $prestasi->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $prestasi->kategori }}</td>
                    </tr>
                    <tr>
                        <th>Pencapaian</th>
                        <td>{{ $prestasi->pencapaian }}</td>
                    </tr>
                    @if(isset($prestasi->deskripsi))
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $prestasi->deskripsi }}</td>
                        </tr>
                    @endif
                    @if(isset($prestasi->tanggal_prestasi))
                        <tr>
                            <th>Tanggal Prestasi</th>
                            <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_prestasi)->translatedFormat('d F Y') }}</td>
                        </tr>
                    @endif
                    @if(isset($prestasi->sertifikat_url) && $prestasi->sertifikat_url)
                        <tr>
                            <th>Sertifikat</th>
                            <td>
                                <a href="{{ Storage::url($prestasi->sertifikat_url) }}" target="_blank" class="btn btn-sm btn-info">Lihat Sertifikat</a>
                            </td>
                        </tr>
                    @endif
                </table>
            @else
                <div class="alert alert-danger">Data prestasi tidak ditemukan.</div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>