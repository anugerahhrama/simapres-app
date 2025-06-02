<form action="{{ route('prestasi.store') }}" method="POST" id="form-tambah-prestasi-modal">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="prestasiModalLabel">Tambah Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
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

        <div class="form-group">
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
            <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" required>
            <small id="error-kategori" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Deskripsi (Opsional)</label> {{-- Tambahkan (Opsional) jika memang begitu --}}
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            <small id="error-tanggal" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Pencapaian</label>
            <input type="text" name="pencapaian" id="pencapaian" class="form-control" required>
            <small id="error-pencapaian" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Evaluasi Diri (Opsional)</label> {{-- Tambahkan (Opsional) jika memang begitu --}}
            <textarea name="evaluasi_diri" id="evaluasi_diri" class="form-control"></textarea>
            <small id="error-evaluasi_diri" class="error-text form-text text-danger"></small>
        </div>
        <input type="hidden" name="status_verifikasi" value="pending">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

---

<script>
    $(document).ready(function() {
        // --- PENTING: SESUAIKAN ID MODAL INI ---
        // Ganti '#tambahPrestasiModal' dengan ID aktual dari elemen modal Bootstrap Anda.
        // Contoh: jika modal Anda adalah <div class="modal fade" id="prestasiModal" ...> maka gunakan '#prestasiModal'
        const modalId = '#prestasiModal'; // <--- PASTIKAN ID MODAL UTAMA ANDA DI SINI

        $('#form-tambah-prestasi-modal').validate({
            rules: {
                lomba_id: {
                    required: true
                },
                nama_kegiatan: {
                    required: true,
                    maxlength: 70 // Contoh batasan panjang
                },
                kategori: {
                    required: true,
                    maxlength: 50 // Contoh batasan panjang
                },
                deskripsi: {
                    // Jika deskripsi opsional, jangan tambahkan 'required: true'
                    maxlength: 255 // Contoh batasan panjang
                },
                tanggal: {
                    required: true,
                    date: true // Pastikan format tanggal valid
                },
                pencapaian: {
                    required: true,
                    maxlength: 50 // Contoh batasan panjang
                },
                evaluasi_diri: {
                    // Jika evaluasi_diri opsional, jangan tambahkan 'required: true'
                    maxlength: 255 // Contoh batasan panjang
                }
                // status_verifikasi tidak perlu aturan karena hidden dan sudah ada value
            },
            messages: {
                lomba_id: {
                    required: "Silakan pilih lomba."
                },
                nama_kegiatan: {
                    required: "Nama kegiatan wajib diisi.",
                    maxlength: "Nama kegiatan tidak boleh lebih dari 70 karakter."
                },
                kategori: {
                    required: "Kategori wajib diisi.",
                    maxlength: "Kategori tidak boleh lebih dari 50 karakter."
                },
                deskripsi: {
                    maxlength: "Deskripsi tidak boleh lebih dari 255 karakter."
                },
                tanggal: {
                    required: "Tanggal wajib diisi.",
                    date: "Format tanggal tidak valid."
                },
                pencapaian: {
                    required: "Pencapaian wajib diisi.",
                    maxlength: "Pencapaian tidak boleh lebih dari 50 karakter."
                },
                evaluasi_diri: {
                    maxlength: "Evaluasi diri tidak boleh lebih dari 255 karakter."
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            // Sembunyikan modal *sebelum* menampilkan SweetAlert
                            $(modalId).modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false, 
                                timer: 1500,
                                allowOutsideClick: false, 
                                allowEscapeKey: false 
                            }).then(() => {
                                if (typeof dataPeriode !== 'undefined' && dataPeriode.ajax && typeof dataPeriode.ajax.reload === 'function') {
                                    dataPeriode.ajax.reload(null, false); // Reload DataTables tanpa reset halaman
                                } else {
                                    // Pilihan jika DataTables tidak ada atau Anda ingin kembali ke halaman sebelumnya
                                    window.history.back();
                                    
                                    // window.location.href = "{{ route('prestasi.index') }}";
                                }
                                // Opsional: Kosongkan form setelah sukses dan sebelum modal ditutup (jika modal tidak direload tiap buka)
                                $(form)[0].reset();
                            });
                        } else {
                            // Menghapus semua pesan error lama
                            $('.error-text').text('');
                            // Menampilkan pesan error dari respons server
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            // Tampilkan SweetAlert untuk error
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle error dari server (misalnya 500 Internal Server Error, atau 419 CSRF token mismatch)
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Jaringan / Server',
                            text: 'Terjadi kesalahan saat berkomunikasi dengan server. Silakan coba lagi. Status: ' + jqXHR.status
                        });
                        console.error("AJAX Error: ", textStatus, errorThrown, jqXHR.responseText);
                    }
                });
                return false;
            },
            errorElement: 'small', // Menggunakan elemen <small> untuk pesan error
            errorPlacement: function(error, element) {
                error.addClass('text-danger error-text'); // Tambahkan kelas CSS Anda
                error.attr('id', 'error-' + element.attr('name')); // Beri ID unik untuk pesan error
                // Hapus pesan error yang mungkin sudah ada sebelumnya untuk elemen ini
                element.closest('.form-group').find('.error-text').remove();
                // Sisipkan pesan error setelah elemen input
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid'); // Tambahkan kelas is-invalid pada input yang error
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid'); // Hapus kelas is-invalid jika valid
                // Juga hapus pesan error text secara manual saat unhighlight
                $(element).closest('.form-group').find('.error-text').text('');
            }
        });
    });
</script>