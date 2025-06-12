@extends('layouts.app')

@section('content')
    <!-- Main Data Table Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                <div class="w-75">
                    <div class="row px-3 mb-3 mt-2">
                        <div class="col-md-3">
                            <select class="form-control" id="filter_tingkatan" name="tingkatan">
                                <option value="">Pilih Tingkatan</option>
                                @foreach ($tingkatanLomba as $item)
                                    <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filter_kategori" name="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="akademik">Akademik</option>
                                <option value="non akademik">Non-Akademik</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body" style="padding: 0;">
                <div class="tab-content p-2">
                    <div class="tab-pane fade show active" id="all">
                        <div class="table-responsive" style="margin: 0 -1px;">
                            <table class="table table-hover align-middle" id="table_lomba">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Lomba</th>
                                        <th>Kategori</th>
                                        <th>Tingkatan</th>
                                        <th>Penyelenggara</th>
                                        <th>Status Verifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="100%" aria-hidden="true"></div>
@endsection

@push('css')
    <style>
        .btn-action {
            margin: 0 2px 5px 0;
        }

        td.text-wrap {
            word-wrap: break-word;
            white-space: normal;
        }
    </style>
@endpush

@push('js')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataTable;
        $(document).ready(function() {
            dataTable = $('#table_lomba').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('verifLomba.list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function(d) {
                        d._token = '{{ csrf_token() }}';
                        d.kategori = $('#filter_kategori').val();
                        d.tingkatan = $('#filter_tingkatan').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    },
                    {
                        data: 'judul',
                        width: "20%"
                    },
                    {
                        data: 'kategori',
                        width: "15%"
                    },
                    {
                        data: 'tingkatan',
                        width: "10%"
                    },
                    {
                        data: 'penyelenggara',
                        className: 'text-wrap',
                        width: "25%"
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        width: "15%",
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false,
                        width: "20%",
                        className: 'text-center'
                    }
                ]
            });

            $('#filter_kategori, #filter_tingkatan').on('change', function() {
                dataTable.ajax.reload();
            });
        });
    </script>
@endpush
