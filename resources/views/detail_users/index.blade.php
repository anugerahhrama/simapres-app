@extends('layouts.app')

@section('content')
    <!-- Stats Cards Row -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stat-header">
                    <span class="stat-title">Total Pengguna</span>
                </div>
                <div class="stat-value">{{ $totalUsers ?? '0' }}</div>
                <div class="stat-subtitle">Total pengguna terdaftar</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stat-header">
                    <span class="stat-title">Total Admin</span>
                </div>
                <div class="stat-value">{{ $totalAdmin ?? '0' }}</div>
                <div class="stat-subtitle">Total admin terdaftar</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stat-header">
                    <span class="stat-title">Total Mahasiswa</span>
                </div>
                <div class="stat-value">{{ $totalMahasiswa ?? '0' }}</div>
                <div class="stat-subtitle">Total mahasiswa terdaftar</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stat-header">
                    <span class="stat-title">Total Dosen</span>
                </div>
                <div class="stat-value">{{ $totalDosen ?? '0' }}</div>
                <div class="stat-subtitle">Total dosen terdaftar</div>
            </div>
        </div>
    </div>

    <!-- Main Data Table Card -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                <div class="d-flex" style="gap: 1rem;">
                    <div class="form-group mb-0">
                        <select class="form-control" id="prodi_id" name="prodi_id" style="min-width: 160px;">
                            <option value="">- Semua Program Studi -</option>
                            @foreach ($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <select class="form-control" id="level_id" name="level_id" style="min-width: 160px;">
                            <option value="">- Semua Level -</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->nama_level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <button onclick="modalAction('{{ route('detailusers.create') }}')" class="btn btn-primary" style="white-space: nowrap;">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah
                    </button>
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
                            <table class="table table-hover" id="table_detailuser">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Induk</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Level</th>
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

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataTable;
        $(document).ready(function() {
            dataTable = $('#table_detailuser').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    "url": "{{ route('detailusers.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi_id = $('#prodi_id').val();
                        d.level_id = $('#level_id').val();
                        d._token = '{{ csrf_token() }}';
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
                        data: "no_induk",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "name",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "20%"
                    },
                    {
                        data: "email",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "prodi",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "20%"
                    },
                    {
                        data: "level",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "10%"
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false,
                        width: "15%"
                    }
                ]
            });

            $('#prodi_id, #level_id').on('change', function() {
                dataTable.ajax.reload();
            });
        });
        
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
