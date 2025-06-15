@extends('layouts.app')

@section('title', 'Create Prestasi')

@section('content')
    <div class="card border-0 shadow-lg my-4" style="border-radius: 10px;">
        <div class="card-body p-4">
            <form action="{{ route('prestasi.store') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
                @csrf

                @php
                    $inputIcon = fn($icon) => "<div class='input-group-prepend'><span class='input-group-text bg-light'><i class='fas fa-$icon text-primary'></i></span></div>";
                @endphp

                <div class="row">
                    {{-- Nama Lomba --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Nama Lomba</label>
                            <div class="input-group">
                                {!! $inputIcon('book') !!}
                                <input type="text" name="nama_lomba" class="form-control border-left-0" placeholder="Masukkan nama lomba" value="{{ old('nama_lomba') }}" required>
                            </div>
                            <small id="error-nama_lomba" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Penyelenggara</label>
                            <div class="input-group">
                                {!! $inputIcon('building') !!}
                                <input type="text" name="penyelenggara" class="form-control border-left-0" placeholder="Nama institusi penyelenggara" value="{{ old('penyelenggara') }}" required>
                            </div>
                            <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Kategori (Tingkatan) --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Kategori / Tingkatan</label>
                            <div class="input-group">
                                {!! $inputIcon('layer-group') !!}
                                <select name="kategori" class="form-control border-left-0" required>
                                    <option value="" disabled selected>-Pilih Kategori-</option>
                                    @foreach ($tingkatan as $item)
                                        <option value="{{ $item->nama }}" {{ old('kategori') == $item->nama ? 'selected' : '' }}>{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="error-kategori" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Tanggal</label>
                            <div class="input-group">
                                {!! $inputIcon('calendar-alt') !!}
                                <input type="date" name="tanggal" class="form-control border-left-0" value="{{ old('tanggal') }}" required>
                            </div>
                            <small id="error-tanggal" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Pencapaian --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Pencapaian</label>
                            <div class="input-group">
                                {!! $inputIcon('medal') !!}
                                <input type="text" name="pencapaian" class="form-control border-left-0" placeholder="Juara 1, Finalis, dll." value="{{ old('pencapaian') }}" required>
                            </div>
                            <small id="error-pencapaian" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Deskripsi (Opsional)</label>
                            <div class="input-group">
                                {!! $inputIcon('align-left') !!}
                                <textarea name="deskripsi" class="form-control border-left-0" placeholder="Deskripsi singkat kegiatan">{{ old('deskripsi') }}</textarea>
                            </div>
                            <small id="error-deskripsi" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Evaluasi Diri --}}
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Evaluasi Diri (Opsional)</label>
                            <div class="input-group">
                                {!! $inputIcon('comments') !!}
                                <textarea name="evaluasi_diri" class="form-control border-left-0" placeholder="Apa yang kamu pelajari dari lomba ini?">{{ old('evaluasi_diri') }}</textarea>
                            </div>
                            <small id="error-evaluasi_diri" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Upload Bukti --}}
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Upload Bukti Prestasi</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="bukti[]" class="custom-file-input" id="exampleInputFile" multiple>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            <small id="error-bukti" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Hidden --}}
                    <input type="hidden" name="status_verifikasi" value="pending">
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('lomba.index') }}" class="btn btn-light border shadow-sm mr-2">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Terdapat beberapa error pada form. Silakan periksa kembali.',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                let label = e.target.nextElementSibling;
                let files = Array.from(e.target.files).map(file => file.name).join(', ');
                label.innerText = files || 'Choose file';
            });
        });
    </script>
@endpush
