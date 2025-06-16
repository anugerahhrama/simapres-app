<form action="{{ $bobot ? route('spk.update', $bobot->id) : route('spk.store') }}" method="{{ $bobot ? 'POST' : 'POST' }}" id="form-bobot">
    @csrf
    @if ($bobot)
        @method('PUT')
    @endif

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">
            <div class="modal-header text-white {{ $bobot ? 'bg-primary' : 'bg-success' }}" style="border-radius: 8px 8px 0 0;">
                <h5 class="modal-title">
                    <i class="fas fa-{{ $bobot ? 'edit' : 'plus' }} mr-2"></i>
                    {{ $bobot ? 'Edit' : 'Tambah' }} Bobot SPK
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="alert alert-warning m-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Perhatian!</strong> Total bobot harus berjumlah 1.0
            </div>

            <div class="modal-body p-4">
                @php
                    $fields = [
                        'c1' => 'Kesesuaian Keahlian',
                        'c2' => 'Jenis Pendaftaran',
                        'c3' => 'Biaya Pendaftaran',
                        'c4' => 'Tingkatan Lomba',
                        'c5' => 'Nilai Benefit',
                    ];
                @endphp

                @foreach ($fields as $field => $label)
                    <div class="form-group">
                        <label class="font-weight-bold">{{ $label }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                            </div>
                            <input value="{{ old($field, optional($bobot)->$field) }}" type="number" step="any" name="{{ $field }}" id="{{ $field }}" class="form-control border-left-0" placeholder="Masukkan angka" required>
                        </div>
                        <small id="error-{{ $field }}" class="error-text form-text text-danger"></small>
                    </div>
                @endforeach
            </div>

            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-light border shadow-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" class="btn btn-{{ $bobot ? 'primary' : 'success' }} shadow-sm">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#form-bobot').validate({
            rules: {
                c1: {
                    required: true,
                    number: true
                },
                c2: {
                    required: true,
                    number: true
                },
                c3: {
                    required: true,
                    number: true
                },
                c4: {
                    required: true,
                    number: true
                },
                c5: {
                    required: true,
                    number: true
                },
            },
            submitHandler: function(form) {
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
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => window.location.reload());
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
