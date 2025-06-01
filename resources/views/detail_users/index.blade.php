@extends('layouts.app')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-tools">
                <button onclick="modalAction('{{ route('detailusers.create') }}')" class="btn btn-sm btn-primary mt-1">Tambah Ajax</button>
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
                            <select class="form-control" id="prodi_id" name="prodi_id">
                                <option value="">- Semua -</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_detailuser">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No Induk</th>
                        <th>Nama Lengkap</th>
                        <th>Prodi</th>
                        <th>Level</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
    {{-- DataTables CSS jika belum dimuat di layout --}}
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
            dataTable = $('#table_detailuser').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('detailusers.list') }}",
                    type: "POST",
                    data: function (d) {
                        d.prodi_id = $('#prodi_id').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'no_induk', name: 'no_induk' },
                    { data: 'name', name: 'name' },
                    { data: 'prodi', name: 'prodi' },
                    { data: 'level', name: 'level' },
                    { data: 'email', name: 'email' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });

            $('#prodi_id').on('change', function () {
                dataTable.ajax.reload();
            });
        });
    </script>
@endpush
