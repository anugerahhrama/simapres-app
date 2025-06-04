    @extends('layouts.app')

    @section('content')
        <div class="card card-outline card-primary">
            <div class="card-header">
            
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group-row">
                            <label class="col-1 control-label col-form-label">Filter Kategori</label>
                            <div class="col-3">
                                {{-- Perhatikan bahwa $kategoris harus dilewatkan dari controller --}}
                                <select class="form-control" id="kategori_filter" name="kategori_filter">
                                    <option value="">- Semua Kategori -</option>
                                    <option value="Regional">Regional</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                    {{-- @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-end bs-success">
                            <a href="{{ route('prestasi.create') }}"  class="btn btn-sm btn-success mt-1">Tambah Prestasi</a>
                        </div>
                    </div>
                <table class="table table-bordered table-striped table-hover table-sm" id="table_prestasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lomba</th>
                            <th>Penyelenggara</th> <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Pencapaian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    @endsection

    @push('css')
        @endpush

    @push('js')
        <script>
            function modalAction(url = '') {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }

            var dataPrestasi; // Ubah nama variabel agar lebih spesifik
            $(document).ready(function() {
                dataPrestasi = $('#table_prestasi').DataTable({ 
                    processing: true,
                    serverSide: true,
                    ajax: {
                        "url": "{{ route('prestasi.list') }}",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d) {
                            // Sesuaikan nama parameter filter dengan yang ada di controller
                            d.kategori_filter = $('#kategori_filter').val();
                        }
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            className: "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: "nama_lomba", // Sesuaikan dengan nama kolom dari controller
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "penyelenggara", // Sesuaikan dengan nama kolom dari controller
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "kategori", // Sesuaikan dengan nama kolom dari controller
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "deskripsi", // Sesuaikan dengan nama kolom dari controller
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "pencapaian", // Sesuaikan dengan nama kolom dari controller. Asumsi ini bukan deskripsi
                            className: "",
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: "aksi",
                            className: "",
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                // Ubah ID filter
                $('#kategori_filter, #status_filter').on('change', function() {
                    dataPrestasi.ajax.reload(); // Reload DataTable saat filter berubah
                });

            
                $('#myModal').on('hidden.bs.modal', function (e) {
                    dataPrestasi.ajax.reload(null, false); // false mempertahankan posisi pagination
                });
            });
        </script>
    @endpush