{{-- resources/views/prestasi/create.blade.php --}}

<form action="{{ route('prestasi.store') }}" method="POST" id="form-tambah">
    @csrf
    {{-- HANYA isi modal-header, modal-body, modal-footer --}}
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        {{-- Mahasiswa ID --}}
        <div class="form-group">
            <label>Mahasiswa</label>
            <select name="mahasiswa_id" id="mahasiswa_id" class="form-control" required>
                <option value="">Pilih Mahasiswa</option>
                @foreach($mahasiswas as $mahasiswa)
                    <option value="{{ $mahasiswa->id }}">{{ $mahasiswa->email }}</option>
                @endforeach
            </select>
            <small id="error-mahasiswa_id" class="error-text form-text text-danger"></small>
        </div>

        {{-- Lomba ID --}}
        <div class="form-group">
            <label>Lomba</label>
            <select name="lomba_id" id="lomba_id" class="form-control" required>
                <option value="">Pilih Lomba</option>
                @foreach($lombas as $lomba)
                    <option value="{{ $lomba->id }}">{{ $lomba->judul }} ({{ $lomba->penyelenggara }})</option>
                @endforeach
            </select>
            <small id="error-lomba_id" class="error-text form-text text-danger"></small>
        </div>

        {{-- Nama Kegiatan (sesuai controller: nama_kegiatan) --}}
        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
            <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
        </div>

        {{-- Kategori (sesuai controller: kategori) --}}
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" required>
            <small id="error-kategori" class="error-text form-text text-danger"></small>
        </div>

        {{-- Deskripsi (sesuai controller: deskripsi) --}}
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
        </div>

        {{-- Tanggal (sesuai controller: tanggal) --}}
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            <small id="error-tanggal" class="error-text form-text text-danger"></small>
        </div>

        {{-- Pencapaian (sesuai controller: pencapaian) --}}
        <div class="form-group">
            <label>Pencapaian</label>
            <input type="text" name="pencapaian" id="pencapaian" class="form-control" required>
            <small id="error-pencapaian" class="error-text form-text text-danger"></small>
        </div>

        {{-- Evaluasi Diri (sesuai controller: evaluasi_diri) --}}
        <div class="form-group">
            <label>Evaluasi Diri</label>
            <textarea name="evaluasi_diri" id="evaluasi_diri" class="form-control"></textarea>
            <small id="error-evaluasi_diri" class="error-text form-text text-danger"></small>
        </div>

        {{-- Status Verifikasi (hidden, default pending) --}}
        <input type="hidden" name="status_verifikasi" value="pending">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" onclick="$('#myModal').modal('hide');">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            // Karena script ini di-inject ke dalam modal, pastikan untuk menginisialisasi ulang
            // validasi dan event handler setiap kali modal dimuat
            $("#form-tambah").validate({
                rules: {
                    mahasiswa_id: {
                        required: true,
                    },
                    lomba_id: {
                        required: true,
                    },
                    nama_kegiatan: {
                        required: true,
                        maxlength: 255
                    },
                    kategori: {
                        required: true,
                        maxlength: 255
                    },
                    tanggal: {
                        required: true,
                        date: true
                    },
                    pencapaian: {
                        required: true,
                        maxlength: 255
                    }
                },
                messages: { 
                    mahasiswa_id: "Pilih mahasiswa yang terkait.",
                    lomba_id: "Pilih lomba yang terkait.",
                    nama_kegiatan: {
                        required: "Nama kegiatan wajib diisi.",
                        maxlength: "Nama kegiatan maksimal 255 karakter."
                    },
                    kategori: {
                        required: "Kategori wajib diisi.",
                        maxlength: "Kategori maksimal 255 karakter."
                    },
                    tanggal: {
                        required: "Tanggal wajib diisi.",
                        date: "Format tanggal tidak valid."
                    },
                    pencapaian: {
                        required: "Pencapaian wajib diisi.",
                        maxlength: "Pencapaian maksimal 255 karakter."
                    }
                },
                submitHandler: function(form) {
                    $('.error-text').text('');
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
                                if (typeof dataPrestasi !== 'undefined' && dataPrestasi.ajax) {
                                    dataPrestasi.ajax.reload();
                                } else {
                                    console.warn('DataTables instance "dataPrestasi" not found or not initialized.');
                                }
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan Validasi',
                                    text: response.message || 'Mohon periksa input Anda.'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan Server',
                                text: 'Gagal menyimpan data. Silakan coba lagi nanti.'
                            });
                        }
                    });
                    return false; 
                },
                errorElement: 'small', 
                errorClass: 'text-danger', 
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                // Tempatkan pesan error di samping input
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "mahasiswa_id" || element.attr("name") == "lomba_id") {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
    @endpush
</form>