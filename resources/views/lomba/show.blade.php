{{-- resources/views/lomba/show.blade.php --}}
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Lomba</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-4">Judul Lomba :</th>
                    <td class="col-8">{{ $lomba->judul ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Tingkatan :</th>
                    <td>{{ $lomba->tingkatanLomba->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Penyelenggara :</th>
                    <td>{{ $lomba->penyelenggara ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Deskripsi :</th>
                    <td>{{ $lomba->deskripsi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Kategori :</th>
                    <td>{{ $lomba->kategori ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Link Registrasi :</th>
                    <td><a href="{{ $lomba->link_registrasi }}" target="_blank">{{ $lomba->link_registrasi ?? '-' }}</a></td>
                </tr>
                <tr>
                    <th class="text-right">Awal Registrasi :</th>
                    <td>{{ $lomba->awal_registrasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Akhir Registrasi :</th>
                    <td>{{ $lomba->akhir_registrasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Bidang Keahlian :</th>
                    <td>{{ $lomba->keahlian->nama_keahlian ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Minat :</th>
                    <td>{{ $lomba->minat->nama_minat ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Jenis Pendaftaran :</th>
                    <td>{{ $lomba->jenis_pendaftaran ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Harga Pendaftaran :</th>
                    <td>{{ $lomba->harga_pendaftaran ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Perkiraan Hadiah :</th>
                    <td>{{ $lomba->perkiraan_hadiah ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Mendapatkan Uang? :</th>
                    <td>{{ $lomba->mendapatkan_uang ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Mendapatkan Sertifikat? :</th>
                    <td>{{ $lomba->mendapatkan_sertifikat ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Nilai Benefit :</th>
                    <td>{{ $lomba->nilai_benefit ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Status Verifikasi :</th>
                    <td>{{ $lomba->status_verifikasi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right">Dibuat Oleh :</th>
                    <td>{{ $lomba->createdBy->detailUser->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
