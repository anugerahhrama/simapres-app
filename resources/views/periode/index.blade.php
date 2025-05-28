@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <button onclick="modalAction('{{ route('periodes.create') }}')" class="btn btn-sm btn-primary mt-1">Tambah
                    Ajax</button>
            </div>
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
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="periode_id" name="periode_id" required>
                                <option value="">- Semua -</option>
                                @foreach ($periodes as $item)
                                    <option value="{{ $item->id }}">{{ $item->tahun_ajaran }} - Semester
                                        {{ $item->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_periode">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

        var dataPeriode;
        $(document).ready(function() {
            dataPeriode = $('#table_periode').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ route('periodes.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.periode_id = $('#periode_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "tahun_ajaran",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "semester",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "tanggal_mulai",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "tanggal_selesai",
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

            $('#periode_id').on('change', function() {
                dataPeriode.ajax.reload();
            });
        });
    </script>
@endpush
