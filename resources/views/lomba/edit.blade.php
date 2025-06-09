@extends('layouts.app')

@section('title', 'Edit Lomba')

@section('content')
    <div class="card border-0 shadow-lg my-4" style="border-radius: 10px;">
        <div class="card-body p-4">
            <form action="{{ route('lomba.update', $lomba->id) }}" method="POST" id="form-edit">
                @csrf
                @method('PUT')

                @php
                    $inputIcon = fn($icon) => "<div class='input-group-prepend'><span class='input-group-text bg-light'><i class='fas fa-$icon'></i></span></div>";
                @endphp

                {{-- Judul Lomba --}}
                <div class="form-group">
                    <label class="font-weight-bold">Judul Lomba</label>
                    <div class="input-group">
                        {!! $inputIcon('book') !!}
                        <input type="text" name="judul" class="form-control border-left-0" value="{{ old('judul', $lomba->judul) }}" required>
                    </div>
                    <small id="error-judul" class="form-text text-danger error-text"></small>
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label class="font-weight-bold">Kategori</label>
                    <div class="input-group">
                        {!! $inputIcon('tags') !!}
                        <select name="kategori" class="form-control border-left-0" required>
                            <option value="akademik" {{ old('kategori', $lomba->kategori) == 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="non akademik" {{ old('kategori', $lomba->kategori) == 'non akademik' ? 'selected' : '' }}>Non Akademik</option>
                        </select>
                    </div>
                    <small id="error-kategori" class="form-text text-danger error-text"></small>
                </div>

                {{-- Tingkatan --}}
                <div class="form-group">
                    <label class="font-weight-bold">Tingkatan</label>
                    <div class="input-group">
                        {!! $inputIcon('layer-group') !!}
                        <select name="tingkatan_lomba_id" class="form-control border-left-0" required>
                            @foreach ($tingkatanLombas as $tingkatan)
                                <option value="{{ $tingkatan->id }}" {{ old('tingkatan_lomba_id', $lomba->tingkatan_lomba_id) == $tingkatan->id ? 'selected' : '' }}>
                                    {{ ucfirst($tingkatan->nama) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <small id="error-tingkatan_lomba_id" class="form-text text-danger error-text"></small>
                </div>

                {{-- Penyelenggara --}}
                <div class="form-group">
                    <label class="font-weight-bold">Penyelenggara</label>
                    <div class="input-group">
                        {!! $inputIcon('building') !!}
                        <input type="text" name="penyelenggara" class="form-control border-left-0" value="{{ old('penyelenggara', $lomba->penyelenggara) }}" required>
                    </div>
                    <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="font-weight-bold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $lomba->deskripsi) }}</textarea>
                    <small id="error-deskripsi" class="form-text text-danger error-text"></small>
                </div>

                {{-- Link Registrasi --}}
                <div class="form-group">
                    <label class="font-weight-bold">Link Registrasi</label>
                    <div class="input-group">
                        {!! $inputIcon('link') !!}
                        <input type="url" name="link_registrasi" class="form-control border-left-0" value="{{ old('link_registrasi', $lomba->link_registrasi) }}" required>
                    </div>
                    <small id="error-link_registrasi" class="form-text text-danger error-text"></small>
                </div>

                {{-- Jadwal Registrasi --}}
                <div class="form-group">
                    <label class="font-weight-bold">Jadwal Registrasi</label>
                    <div class="input-group">
                        {!! $inputIcon('calendar-alt') !!}
                        <input type="text" name="jadwal_registrasi" class="form-control border-left-0" id="reservation" value="{{ old('awal_registrasi', $lomba->awal_registrasi) . ' - ' . old('akhir_registrasi', $lomba->akhir_registrasi) }}">
                    </div>
                </div>

                {{-- Keahlian --}}
                <div class="form-group">
                    <label class="font-weight-bold">Bidang Keahlian</label>
                    <select class="select2bs4 form-control" name="keahlian[]" multiple="multiple" style="width: 100%;">
                        @foreach ($keahlians as $keahlian)
                            <option value="{{ $keahlian->id }}" {{ in_array($keahlian->id, old('keahlian', $lomba->keahlian->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $keahlian->nama_keahlian }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Pendaftaran --}}
                <div class="form-group">
                    <label class="font-weight-bold">Jenis Pendaftaran</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_pendaftaran" value="individu" {{ old('jenis_pendaftaran', $lomba->jenis_pendaftaran) == 'individu' ? 'checked' : '' }}>
                        <label class="form-check-label">Individu</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_pendaftaran" value="tim" {{ old('jenis_pendaftaran', $lomba->jenis_pendaftaran) == 'tim' ? 'checked' : '' }}>
                        <label class="form-check-label">Tim</label>
                    </div>
                </div>

                {{-- Jenis Biaya --}}
                <div class="form-group">
                    <label class="font-weight-bold">Jenis Biaya</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_biaya" value="gratis" {{ old('jenis_biaya', $lomba->harga_pendaftaran == 0 ? 'gratis' : 'berbayar') == 'gratis' ? 'checked' : '' }}>
                        <label class="form-check-label">Gratis</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_biaya" value="berbayar" {{ old('jenis_biaya', $lomba->harga_pendaftaran == 0 ? 'gratis' : 'berbayar') == 'berbayar' ? 'checked' : '' }}>
                        <label class="form-check-label">Berbayar</label>
                    </div>
                </div>

                {{-- Harga Pendaftaran --}}
                <div class="form-group" id="formHarga" style="{{ old('jenis_biaya', $lomba->harga_pendaftaran == 0 ? 'gratis' : 'berbayar') == 'berbayar' ? '' : 'display: none;' }}">
                    <label class="font-weight-bold">Harga Pendaftaran</label>
                    <div class="input-group">
                        {!! $inputIcon('money-bill-wave') !!}
                        <input type="number" name="harga_pendaftaran" class="form-control border-left-0" value="{{ old('harga_pendaftaran', $lomba->harga_pendaftaran) }}">
                    </div>
                </div>

                {{-- Hadiah --}}
                <div class="form-group">
                    <label class="font-weight-bold">Hadiah</label>
                    <select class="select2bs4-2 form-control" name="hadiah[]" multiple="multiple" style="width: 100%;">
                        @php $selectedHadiah = old('hadiah', $lomba->hadiah ?? []); @endphp
                        <option value="uang" {{ in_array('uang', $selectedHadiah) ? 'selected' : '' }}>Uang</option>
                        <option value="trofi" {{ in_array('trofi', $selectedHadiah) ? 'selected' : '' }}>Trofi</option>
                        <option value="sertifikat" {{ in_array('sertifikat', $selectedHadiah) ? 'selected' : '' }}>Sertifikat</option>
                    </select>
                </div>

                {{-- Status Verifikasi --}}
                <div class="form-group">
                    <label class="font-weight-bold">Status Verifikasi</label>
                    <select name="status_verifikasi" class="form-control" required>
                        <option value="pending" {{ old('status_verifikasi', $lomba->status_verifikasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ old('status_verifikasi', $lomba->status_verifikasi) == 'verified' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ old('status_verifikasi', $lomba->status_verifikasi) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('lomba.index') }}" class="btn btn-light border shadow-sm mr-2">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#reservation').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: ' - '
                }
            });

            $('.select2bs4').select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: "Pilih atau buat tag baru",
                allowClear: true,
                theme: 'bootstrap4'
            });

            $('.select2bs4-2').select2({
                allowClear: true,
                theme: 'bootstrap4'
            });

            $('input[name="jenis_biaya"]').on('change', function() {
                if ($(this).val() === 'berbayar') {
                    $('#formHarga').show();
                } else {
                    $('#formHarga').hide();
                }
            });

            $('input[name="jenis_pendaftaran"]').on('change', function() {
                if ($(this).val() === 'berbayar') {
                    $('#formHarga').show();
                } else {
                    $('#formHarga').hide();
                }
            });
        });
    </script>

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

@endpush
