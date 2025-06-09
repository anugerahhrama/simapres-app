<form action="{{ route('profile.keahlian.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-regular fa-circle-plus mr-2"></i>Tambah Keahlian
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group">
                    <label>Pilih Keahlian atau tambahkan data baru</label>
                    <select class="select2bs4" name="keahlian[]" multiple="multiple" data-placeholder="Pilih atau buat tag baru" style="width: 100%;">
                        @foreach ($allKeahlian as $keahlian)
                            <option value="{{ $keahlian->id }}">{{ $keahlian->nama_keahlian }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.select2bs4').select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: "Pilih atau buat tag baru",
            allowClear: true,
            theme: 'bootstrap4'
        });

        $("#form-tambah").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server.'
                        });
                    }
                });
                return false;
            }
        });
    });
</script>
