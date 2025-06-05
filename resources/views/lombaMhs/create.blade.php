<form action="{{ route('lombas.store') }}" method="POST" id="form-tambah-lomba">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Lomba</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="judul">Judul Lomba</label>
            <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan judul lomba" required>
            <small id="error-judul" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <option value="Akademik">Akademik</option>
                <option value="Non Akademik">Non Akademik</option>
            </select>
            <small id="error-kategori" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="penyelenggara">Penyelenggara</label>
            <input type="text" name="penyelenggara" id="penyelenggara" class="form-control" placeholder="Masukkan nama penyelenggara" required>
            <small id="error-penyelenggara" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsikan lomba secara detail" required></textarea>
            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="link_registrasi">Link Registrasi (Opsional)</label>
            <input type="url" name="link_registrasi" id="link_registrasi" class="form-control" placeholder="Contoh: https://example.com/daftar-lomba">
            <small id="error-link_registrasi" class="error-text form-text text-danger"></small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="awal_registrasi">Awal Registrasi</label>
                    <input type="date" name="awal_registrasi" id="awal_registrasi" class="form-control" required>
                    <small id="error-awal_registrasi" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="akhir_registrasi">Akhir Registrasi</label>
                    <input type="date" name="akhir_registrasi" id="akhir_registrasi" class="form-control" required>
                    <small id="error-akhir_registrasi" class="error-text form-text text-danger"></small>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="tingkatan_lomba_id">Tingkatan Lomba</label>
            <select name="tingkatan_lomba_id" id="tingkatan_lomba_id" class="form-control" required>
                <option value="">Pilih Tingkatan Lomba</option>
                @foreach ($tingkatanLombas as $tingkatan)
                    <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama_tingkatan }}</option>
                @endforeach
            </select>
            <small id="error-tingkatan_lomba_id" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="bidang_keahlian_id">Bidang Keahlian</label>
            <select name="bidang_keahlian_id" id="bidang_keahlian_id" class="form-control" required>
                <option value="">Pilih Bidang Keahlian</option>
                @foreach ($keahlians as $keahlian)
                    <option value="{{ $keahlian->id }}">{{ $keahlian->nama_keahlian }}</option>
                @endforeach
            </select>
            <small id="error-bidang_keahlian_id" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="minat_id">Minat</label>
            <select name="minat_id" id="minat_id" class="form-control" required>
                <option value="">Pilih Minat</option>
                @foreach ($minats as $minat)
                    <option value="{{ $minat->id }}">{{ $minat->nama_minat }}</option>
                @endforeach
            </select>
            <small id="error-minat_id" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="status_verifikasi">Status Verifikasi</label>
            <select name="status_verifikasi" id="status_verifikasi" class="form-control" required>
                <option value="">Pilih Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <small id="error-status_verifikasi" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="jenis_pendaftaran">Jenis Pendaftaran</label>
            <select name="jenis_pendaftaran" id="jenis_pendaftaran" class="form-control" required>
                <option value="">Pilih Jenis Pendaftaran</option>
                <option value="Online">Online</option>
                <option value="Offline">Offline</option>
            </select>
            <small id="error-jenis_pendaftaran" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="harga_pendaftaran">Harga Pendaftaran (Rp.)</label>
            <input type="number" name="harga_pendaftaran" id="harga_pendaftaran" class="form-control" min="0" value="0" required>
            <small id="error-harga_pendaftaran" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="perkiraan_hadiah">Perkiraan Hadiah (Opsional)</label>
            <input type="text" name="perkiraan_hadiah" id="perkiraan_hadiah" class="form-control" placeholder="Contoh: Uang tunai, sertifikat, merchandise">
            <small id="error-perkiraan_hadiah" class="error-text form-text text-danger"></small>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-check">
                    <input type="checkbox" name="mendapatkan_uang" id="mendapatkan_uang" class="form-check-input" value="1">
                    <label class="form-check-label" for="mendapatkan_uang">Mendapatkan Uang?</label>
                    <small id="error-mendapatkan_uang" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group form-check">
                    <input type="checkbox" name="mendapatkan_sertifikat" id="mendapatkan_sertifikat" class="form-check-input" value="1">
                    <label class="form-check-label" for="mendapatkan_sertifikat">Mendapatkan Sertifikat?</label>
                    <small id="error-mendapatkan_sertifikat" class="error-text form-text text-danger"></small>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="nilai_benefit">Nilai Benefit (0-100)</label>
            <input type="number" name="nilai_benefit" id="nilai_benefit" class="form-control" min="0" max="100" required>
            <small id="error-nilai_benefit" class="error-text form-text text-danger"></small>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#form-tambah-lomba').submit(function(e) {
            e.preventDefault(); // Mencegah submit form bawaan

            // Hapus semua pesan error sebelumnya
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(), // Mengambil semua data form
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#myModal').modal('hide'); // Tutup modal
                        alert(response.message); // Tampilkan alert sukses (bisa diganti dengan SweetAlert)
                        // Reload DataTable jika ada di halaman induk, atau panggil fungsi yang merefresh
                        // Pastikan 'dataTable' adalah variabel global yang menyimpan instance DataTables Anda
                        if (typeof dataTable !== 'undefined' && dataTable instanceof $.fn.dataTable.Api) {
                            dataTable.ajax.reload(null, false);
                        } else {
                            // Fallback jika dataTable belum didefinisikan atau tidak ditemukan
                            location.reload();
                        }
                    } else {
                        alert('Terjadi kesalahan: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        // Tampilkan pesan error validasi
                        $.each(response.errors, function(key, value) {
                            $('#error-' + key).text(value);
                            $('#' + key).addClass('is-invalid'); // Tambahkan kelas is-invalid untuk highlight input
                        });
                    } else {
                        alert('Terjadi kesalahan tak terduga: ' + (response.message || ''));
                    }
                }
            });
        });
    });
</script>