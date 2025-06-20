@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-lg my-4" style="border-radius: 10px;">
        <div class="card-body p-4">
            <h6 class="mb-4 font-weight-bold">
                Kategori: <span>{{ $lomba->jenis_pendaftaran }}</span>
            </h6>
            <form action="{{ route('pendaftaran.store') }}" method="POST" id="form-tambah">
                @csrf

                @php
                    $inputIcon = fn($icon) => "<div class='input-group-prepend'><span class='input-group-text bg-light'><i class='fas fa-$icon text-primary'></i></span></div>";
                @endphp
                <input type="hidden" name="lomba_id" value="{{ $lomba->id }}">
                <div class="row">
                    {{-- Judul Lomba --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Judul Lomba</label>
                            <div class="input-group">
                                {!! $inputIcon('book') !!}
                                <input type="text" name="judul" class="form-control border-left-0" placeholder="Judul lomba" value="{{ $lomba->judul }}" readonly required>
                            </div>
                            <small id="error-judul" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Penyelenggara</label>
                            <div class="input-group">
                                {!! $inputIcon('building') !!}
                                <input type="text" name="penyelenggara" class="form-control border-left-0" placeholder="Masukkan penyelenggara lomba" value="{{ $lomba->penyelenggara }}" readonly required>
                            </div>
                            <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Email peserta --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Email</label>
                            <div class="input-group">
                                {!! $inputIcon('user') !!}
                                <input type="text" name="email" class="form-control border-left-0" placeholder="Email Peserta lomba" value="{{ auth()->user()->email }}" readonly required>
                            </div>
                            <small id="error-email" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Nama peserta --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Nama</label>
                            <div class="input-group">
                                {!! $inputIcon('book') !!}
                                <input type="text" name="nama" class="form-control border-left-0" placeholder="Nama Peserta lomba" value="{{ auth()->user()->detailUser->name }}" readonly required>
                            </div>
                            <small id="error-nama" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Daftar
                </button>
            </form>
        </div>
    </div>
@endsection
