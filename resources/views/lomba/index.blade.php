@extends('layouts.app')

@section('content')
    <!-- Main Data Table Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                <div></div>
                <div>
                    <form action="{{ route('lomba.create') }}" method="get" style="display: inline;">
                        <button type="submit" class="btn btn-primary" style="font-weight: 500; padding: 6px 16px; font-size: 14px;">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah
                    </button>
                </form>
                </div>
            </div>

            <div class="card-body" style="padding: 0;">
                @if (session('success'))
                    <div class="alert alert-success mx-2 mt-2" style="border-radius: 6px;">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mx-2 mt-2" style="border-radius: 6px;">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row px-3 mb-3 mt-2">
                    <div class="col-md-3">
                        <select class="form-control" id="filter_tingkatan">
                            <option value="">Pilih Tingkatan</option>
                            <option value="pemula">Pemula</option>
                            <option value="lokal">Lokal</option>
                            <option value="regional">Regional</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filter_kategori">
                            <option value="">Pilih Kategori</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Non-Akademik">Non-Akademik</option>
                        </select>
                    </div>
                </div>

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

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="100%" aria-hidden="true"></div>
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
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var dataTable;
        $(document).ready(function () {
            dataTable = $('#table_lomba').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('lomba.list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function (d) {
                        d._token = '{{ csrf_token() }}';
                        d.kategori = $('#filter_kategori').val();
                        d.tingkatan = $('#filter_tingkatan').val();
                    }
                },
                columns: [
                    {
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
                        data: 'status_verifikasi',
                        className: 'text-center',
                        width: "15%",
                        render: function (data, type, row) {
                            if (data === 'Terverifikasi') {
                                return '<span class="badge badge-success" style="font-size: 12px;">Terverifikasi</span>';
                            } else {
                                return '<span class="badge badge-secondary" style="font-size: 12px;">Belum Terverifikasi</span>';
                            }
                        }
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

            $('#filter_kategori, #filter_tingkatan').on('change', function () {
                dataTable.ajax.reload();
            });
        });
    </script>
@endpush
