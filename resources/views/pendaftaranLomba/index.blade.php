@extends('layouts.app')

@section('content')
    <!-- Main Data Table Card -->
    <div class="card mt-4">
        <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
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
                            <table class="table table-hover" id="table_pendaftaranLomba">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Nama Lomba</th>
                                        <th>Penyelenggara</th>
                                        <th>Dosen Pembimbing</th>
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

@push('js')
    <script>
        var dataPendaftaranLomba;
        $(document).ready(function() {
            dataPendaftaranLomba = $('#table_pendaftaranLomba').DataTable({
                serverSide: true,
                scrollX: false,
                scrollCollapse: true,
                autoWidth: false,
                ajax: {
                    "url": "{{ route('pendaftaranLomba.list') }}",
                    "dataType": "json",
                    "type": "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    },
                    {
                        data: "nama_mahasiswa",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "20%"
                    },
                    {
                        data: "program_studi",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "nama_lomba",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "penyelenggara",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "dosen_pembimbing",
                        className: "",
                        orderable: true,
                        searchable: true,
                        width: "15%"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        width: "15%"
                    }
                ]
            });
        });

        function modalAction(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#myModal').html(response);
                    $('#myModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat memuat data');
                }
            });
        }
    </script>
@endpush
