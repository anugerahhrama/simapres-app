@extends('layouts.app')

@section('content')
    <!-- Main Data Table Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
                <div class="form-group mb-0 mr-3">
                    <select class="form-control" id="level_id" name="level_id" style="min-width: 180px;">
                        <option value="">- Semua Level -</option>
                        @foreach ($levels as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_level }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button onclick="modalAction('{{ route('levels.create') }}')" class="btn btn-primary" style="white-space: nowrap;">
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
                            <table class="table table-hover" id="table_level">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Level Kode</th>
                                        <th>Level Nama</th>
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

        var dataLevel;
        $(document).ready(function() {
            dataLevel = $('#table_level').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    "url": "{{ route('levels.list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.level_id = $('#level_id').val();
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
                        data: "level_code",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "25%"
                    },
                    {
                        data: "nama_level",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "50%"
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false,
                        width: "20%"
                    }
                ]
            });

            $('#level_id').on('change', function() {
                dataLevel.ajax.reload();
            });
        });
    </script>
@endpush
