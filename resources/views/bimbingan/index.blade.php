@extends('layouts.app')

@section('content')
    <!-- Main Data Table Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                <div class="form-group mb-0 mr-3">
                    <select class="form-control" id="status_filter" name="status_filter" style="min-width: 180px;">
                        <option value="">- Semua Status -</option>
                        <option value="0">Belum Mulai</option>
                        <option value="1">Berjalan</option>
                        <option value="2">Selesai</option>
                    </select>
                </div>
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

            <div class="tab-content p-2">
                <div class="tab-pane fade show active" id="all">
                    <div class="table-responsive" style="margin: 0 -1px;">
                        <table class="table table-hover" id="table_bimbingan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    @if (auth()->user()->level->level_code == 'ADM')
                                        <th>Dosen Pembimbing</th>
                                    @endif
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data akan diisi oleh DataTables --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="100%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataBimbingan;
        $(document).ready(function() {
            dataBimbingan = $('#table_bimbingan').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    "url": "{{ route('bimbingan.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.status = $('#status_filter').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    },
                    {
                        data: "mahasiswa",
                        orderable: true,
                        searchable: true,
                        width: "20%"
                    },
                    @if (auth()->user()->level->level_code == 'ADM')
                        {
                            data: "dosen",
                            orderable: true,
                            searchable: true,
                            width: "20%"
                        },
                    @endif 
                    {
                        data: "tanggal_mulai",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "tanggal_selesai",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "status",
                        orderable: true,
                        searchable: true,
                        width: "10%",
                        render: function(data, type, row) {
                            if (data == 0)
                                return '<span class="badge badge-secondary">Belum Mulai</span>';
                            if (data == 1)
                                return '<span class="badge badge-warning">Berjalan</span>';
                            if (data == 2)
                                return '<span class="badge badge-success">Selesai</span>';
                            return data;
                        }
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "20%"
                    }
                ]
            });

            $('#status_filter').on('change', function() {
                dataBimbingan.ajax.reload();
            });
        });
    </script>
@endpush
