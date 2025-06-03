@extends('layouts.app')

@section('content')
    <div class="row px-2">
        <!-- Profile Image -->
        <div class="col-sm-4 card card-primary card-outline h-100 mt-4 mr-lg-2">
            <div class="card-body box-profile">
                <div class="position-relative mx-auto" style="width: 128px; height: 128px;">
                    @if ($user->detailUser?->photo_file)
                        <img src="{{ asset('storage/' . $user->detailUser->photo_file) }}" class="rounded-circle border border-white shadow-sm w-100 h-100 object-fit-cover" alt="User profile picture">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->detailUser?->name ?? $user->name) }}&background=667eea&color=fff" class="rounded-circle border border-white shadow-sm w-100 h-100 object-fit-cover" alt="Default avatar">
                    @endif

                    <!-- Pencil icon -->
                    <div class="position-absolute bg-white border rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="bottom: 0; right: 0; width: 32px; height: 32px; cursor: pointer;" title="Edit photo">
                        <i class="fas fa-pencil-alt text-secondary small"></i>
                    </div>
                </div>


                <h3 class="profile-username text-center">
                    {{ $user->detailUser->name }}
                </h3>

                <p class="text-muted text-center">
                    {{ $user->detailUser->prodi->name }}
                </p>

                <table class="table table-md table-borderless">
                    <tr>
                        <th class="text-left col-3">No Induk</th>
                        <td>:</td>
                        <td class="col-9">{{ $user->detailUser->no_induk }}</td>
                    </tr>
                    <tr>
                        <th class="text-left col-3">Phone</th>
                        <td>:</td>
                        <td class="col-9">{{ $user->detailUser->phone }}</td>
                    </tr>
                </table>

                <button type="button" onclick="modalAction('{{ route('profile.edit', $user->id) }}')" class="btn btn-primary w-100 mt-4">Edit Profile</button>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <div class="col card card-primary card-outline h-100 p-4 mt-4">
            <h4>
                Minat Ajang lomba
            </h4>
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
    </script>
@endpush
