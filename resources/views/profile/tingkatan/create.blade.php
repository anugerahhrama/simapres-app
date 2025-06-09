<form action="{{ route('profile.tingkatan.store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-dark" style="background-color: #f0f5fe; border-radius: 8px 8px 0 0;">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fas fa-regular fa-circle-plus mr-2"></i>Tingkatan Lomba
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <label class="font-weight-bold d-block mb-2">
                    Urutkan preferensi tingkatan lomba Anda (maksimal 3)
                </label>
                <small class="form-text text-muted mb-3">
                    Drag dan urutkan tingkatan lomba dari yang paling diminati ke yang paling rendah.
                    Urutan pertama akan dianggap sebagai pilihan utama.
                </small>
                <ul id="sortable-list" class="list-group mb-3">
                    @foreach ($tingkatan as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $item->id }}">
                            <span>{{ $item->nama }}</span>
                            <i class="fas fa-bars handle" style="cursor: move;"></i>
                        </li>
                    @endforeach
                </ul>

                @for ($i = 0; $i < 3; $i++)
                    <input type="hidden" name="urutan[]" id="urutan_{{ $i }}">
                @endfor
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
        $('#sortable-list').sortable({
            handle: '.handle',
            update: updateHiddenInputs
        });

        function updateHiddenInputs() {
            const ids = $('#sortable-list li')
                .map(function() {
                    return $(this).data('id');
                }).get().slice(0, 3);

            ids.forEach(function(val, i) {
                $('#urutan_' + i).val(val);
            });
        }

        updateHiddenInputs();

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
