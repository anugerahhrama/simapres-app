@extends('layouts.app')

@section('content')
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row px-2">
        <!-- Profile Image -->
        <div class="col-sm-4">
            <div class="card card-primary card-outline mt-4 mr-lg-2">
                <div class="card-body box-profile">
                    <div class="position-relative mx-auto" style="width: 128px; height: 128px;">
                        @if ($user->detailUser?->photo_file)
                            <img src="{{ asset('storage/' . $user->detailUser->photo_file) }}" class="rounded-circle border border-white shadow-sm w-100 h-100" style="object-fit: cover; object-position: center" alt="User profile picture">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->detailUser?->name ?? $user->name) }}&background=667eea&color=fff" class="rounded-circle border border-white shadow-sm w-100 h-100 object-fit-cover" alt="Default avatar">
                        @endif

                        <!-- Pencil icon -->
                        <button type="button" onclick="modalAction('{{ route('profile.photo.edit', $user->id) }}')" class="position-absolute bg-white border rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="bottom: 0; right: 0; width: 32px; height: 32px; cursor: pointer;" title="Edit photo">
                            <i class="fas fa-pencil-alt text-secondary small"></i>
                        </button>
                    </div>

                    <h3 class="profile-username text-center">
                        {{ $user->detailUser->name }}
                    </h3>
                    @if (auth()->user()->level->level_code !== 'ADM')
                        <p class="text-muted text-center">
                            {{ $user->detailUser?->prodi?->name }}
                        </p>

                        <table class="table table-md table-borderless">
                            <tr>
                                <th class="text-left col-3">No Induk</th>
                                <td>:</td>
                                <td class="col-9">{{ $user->detailUser?->no_induk }}</td>
                            </tr>
                            <tr>
                                <th class="text-left col-3">Phone</th>
                                <td>:</td>
                                <td class="col-9">{{ $user->detailUser?->phone }}</td>
                            </tr>
                        </table>

                        <button type="button" onclick="modalAction('{{ route('profile.edit', $user->id) }}')" class="btn btn-primary w-100 mt-4">Edit Profile</button>
                    @endif
                </div>
            </div>

            <div class="card card-primary card-outline p-4 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>
                        Tentukan Bobot Spk
                    </h4>
                    <button type="button" onclick="modalAction('{{ route('spk.edit', auth()->user()->id) }}')" class="btn btn-primary" style="font-size: 12px;"><i class="far fa-solid fa-gear"></i></button>
                </div>

                <div class="mt-4">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-start align-items-center">
                            <strong class="mr-1">C1 :</strong> {{ $bobot->c1 ?? '' }}
                        </li>
                        <li class="list-group-item d-flex justify-content-start align-items-center">
                            <strong class="mr-1">C2 :</strong> {{ $bobot->c2 ?? '' }}
                        </li>
                        <li class="list-group-item d-flex justify-content-start align-items-center">
                            <strong class="mr-1">C3 :</strong> {{ $bobot->c3 ?? '' }}
                        </li>
                        <li class="list-group-item d-flex justify-content-start align-items-center">
                            <strong class="mr-1">C4 :</strong> {{ $bobot->c4 ?? '' }}
                        </li>
                        <li class="list-group-item d-flex justify-content-start align-items-center">
                            <strong class="mr-1">C5 :</strong> {{ $bobot->c5 ?? '' }}
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="col h-100 {{ auth()->user()->level->level_code !== 'ADM' ? '' : 'd-none' }}">
            <div class="card mt-4 px-4 py-2">
                <h4 class="m-0 p-0">Preferensi</h4>
            </div>
            <div class="card card-primary card-outline p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>
                        Kriteria Keahlian
                    </h4>
                    <button type="button" onclick="modalAction('{{ route('profile.keahlian.create') }}')" class="btn btn-primary" style="font-size: 12px;"><i class="far fa-solid fa-plus"></i></button>
                </div>

                <div class="row mt-4">
                    @forelse ($user->keahlian as $uk)
                        <div class="col-md-3">
                            <div class="card py-1 px-3 shadow-sm border-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $uk->keahlian->nama_keahlian }}</h6>

                                    <button type="button" class="btn btn-sm btn-outline-none btn-delete" title="Hapus" data-id="{{ $uk->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">Belum ada keahlian ditambahkan.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="card card-primary card-outline p-4 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>
                        Kriteria Tingkat Lomba
                    </h4>
                    <button type="button" onclick="modalAction('{{ route('profile.tingkatan.create') }}')" class="btn btn-primary" style="font-size: 12px;"><i class="far fa-solid fa-gear"></i></button>
                </div>

                @php
                    $tingkatan = $user->preferensiTingkatLomba;
                @endphp

                <div class="mt-4">
                    @if ($tingkatan)
                        <ul class="list-group">
                            @if ($tingkatan->tingkatSatu)
                                <li class="list-group-item d-flex justify-content-start align-items-center">
                                    <strong class="mr-1">1. </strong> {{ $tingkatan->tingkatSatu->nama }}
                                </li>
                            @endif
                            @if ($tingkatan->tingkatDua)
                                <li class="list-group-item d-flex justify-content-start align-items-center">
                                    <strong class="mr-1">2. </strong> {{ $tingkatan->tingkatDua->nama }}
                                </li>
                            @endif
                            @if ($tingkatan->tingkatTiga)
                                <li class="list-group-item d-flex justify-content-start align-items-center">
                                    <strong class="mr-1">3. </strong> {{ $tingkatan->tingkatTiga->nama }}
                                </li>
                            @endif
                        </ul>
                    @else
                        <div class="alert alert-info">Kamu belum memilih preferensi tingkatan lomba.</div>
                    @endif
                </div>

            </div>

            <div class="card card-primary card-outline p-4 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>
                        Kriteria Jenis Pendaftaran
                    </h4>
                    <button type="button" onclick="modalAction('{{ route('profile.jenis.create') }}')" class="btn btn-primary" style="font-size: 12px;">
                        <i class="far fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="mt-4">
                    @if ($user->jenis)
                        <div class="alert alert-info">
                            Jenis pendaftaran yang dipilih: <strong>{{ ucfirst($user->jenis->jenis_pendaftaran) }}</strong>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada preferensi jenis pendaftaran.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Preferensi Biaya --}}
            <div class="card card-primary card-outline p-4 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Kriteria Biaya Pendaftaran</h4>
                    <button type="button" onclick="modalAction('{{ route('profile.biaya.create') }}')" class="btn btn-primary" style="font-size: 12px;">
                        <i class="far fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="mt-4">
                    @if ($user->biaya)
                        <div class="alert alert-info">
                            Biaya pendaftaran yang dipilih: <strong>{{ ucfirst($user->biaya->biaya) }}</strong>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada preferensi biaya pendaftaran.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Preferensi Hadiah --}}
            <div class="card card-primary card-outline p-4 mt-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Kriteria Hadiah/Benefit</h4>
                    <button type="button" onclick="modalAction('{{ route('profile.hadiah.create') }}')" class="btn btn-primary" style="font-size: 12px;">
                        <i class="far fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="mt-4">
                    @if ($user->hadiah)
                        <div class="alert alert-info">
                            Hadiah/benefit yang dipilih: <strong>{{ ucfirst($user->hadiah->hadiah) }}</strong>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Belum ada preferensi hadiah/benefit.
                        </div>
                    @endif
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

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let url = `{{ route('profile.keahlian.destroy', '__ID__') }}`.replace('__ID__', id);

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.status) {
                                Swal.fire('Terhapus!', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-delete-preferensi', function() {
            let id = $(this).data('id');
            let type = $(this).data('type');
            let url = '';
            if (type === 'jenis') {
                url = `{{ route('profile.jenis.destroy', '__ID__') }}`.replace('__ID__', id);
            } else if (type === 'biaya') {
                url = `{{ route('profile.biaya.destroy', '__ID__') }}`.replace('__ID__', id);
            } else if (type === 'hadiah') {
                url = `{{ route('profile.hadiah.destroy', '__ID__') }}`.replace('__ID__', id);
            }

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.status) {
                                Swal.fire('Terhapus!', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('submit', '#form-tambah-jenis, #form-tambah-biaya, #form-tambah-hadiah', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.post(url, data, function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.message
                    });
                }
            });
        });
    </script>
@endpush
