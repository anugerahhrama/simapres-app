@extends('layouts.app')

@section('title', 'Create Lomba')

@section('content')
    <div class="card border-0 shadow-lg my-4" style="border-radius: 10px;">
        <div class="card-body p-4">
            <form action="{{ route('lomba.store') }}" method="POST" id="form-tambah">
                @csrf

                @php
                    $inputIcon = fn($icon) => "<div class='input-group-prepend'><span class='input-group-text bg-light'><i class='fas fa-$icon text-primary'></i></span></div>";
                @endphp

                <div class="row">
                    {{-- Judul Lomba --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Judul Lomba</label>
                            <div class="input-group">
                                {!! $inputIcon('book') !!}
                                <input type="text" name="judul" class="form-control border-left-0" placeholder="Masukkan judul lomba" value="{{ old('judul') }}" required>
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
                                <input type="text" name="penyelenggara" class="form-control border-left-0" placeholder="Masukkan penyelenggara lomba" value="{{ old('penyelenggara') }}" required>
                            </div>
                            <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Link Registrasi --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Link Registrasi</label>
                            <div class="input-group">
                                {!! $inputIcon('link') !!}
                                <input type="url" name="link_registrasi" class="form-control border-left-0" placeholder="https://contoh.com" required>
                            </div>
                            <small id="error-link_registrasi" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Jadwal Registrasi --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Jadwal Registrasi</label>
                            <div class="input-group">
                                {!! $inputIcon('calendar-alt') !!}
                                <input type="text" name="jadwal_registrasi" class="form-control border-left-0" id="reservation" value="{{ old('awal_registrasi') && old('akhir_registrasi') ? old('awal_registrasi') . ' - ' . old('akhir_registrasi') : '' }}" placeholder="Pilih rentang tanggal registrasi">
                            </div>
                            @if ($errors->has('awal_registrasi') || $errors->has('akhir_registrasi'))
                                <small class="form-text text-danger">
                                    {{ $errors->first('awal_registrasi') ?: $errors->first('akhir_registrasi') }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Tingkatan --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Tingkatan</label>
                            <div class="input-group">
                                {!! $inputIcon('layer-group') !!}
                                <select name="tingkatan_lomba_id" class="form-control border-left-0" required>
                                    <option value="" disabled {{ old('tingkatan_lomba_id') ? '' : 'selected' }}>--
                                        Pilih Tingkatan --</option>
                                    @foreach ($tingkatanLombas as $tingkatan)
                                        <option value="{{ $tingkatan->id }}" {{ old('tingkatan_lomba_id') == $tingkatan->id ? 'selected' : '' }}>
                                            {{ ucfirst($tingkatan->nama) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="error-tingkatan_lomba_id" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Kategori</label>
                            <div class="input-group">
                                {!! $inputIcon('tags') !!}
                                <select name="kategori" class="form-control border-left-0" required>
                                    <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>-- Pilih
                                        Kategori --</option>
                                    <option value="Akademik" {{ old('kategori') == 'akademik' ? 'selected' : '' }}>Akademik
                                    </option>
                                    <option value="Non akademik" {{ old('kategori') == 'non akademik' ? 'selected' : '' }}>
                                        Non Akademik</option>
                                </select>
                            </div>
                            <small id="error-kategori" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Keahlian --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Bidang Keahlian</label>
                            <select class="select2bs4 form-control" name="keahlian[]" multiple="multiple" data-placeholder="Pilih keahlian" style="width: 100%;" required>
                                @foreach ($keahlians as $keahlian)
                                    <option value="{{ $keahlian->id }}" {{ collect(old('keahlian', []))->contains($keahlian->id) ? 'selected' : '' }}>
                                        {{ $keahlian->nama_keahlian }}
                                    </option>
                                @endforeach
                                {{-- Tambahkan input manual --}}
                                @foreach (old('keahlian', []) as $val)
                                    @if (!is_numeric($val))
                                        <option value="{{ $val }}" selected>{{ $val }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <small id="error-keahlian_id" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- Hadiah --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Hadiah</label>
                            <select id="hadiahSelect" class="select2bs4-2 form-control" name="hadiah[]" multiple="multiple" data-placeholder="Pilih hadiah" style="width: 100%;" required>
                                <option value="uang" {{ in_array('uang', old('hadiah', [])) ? 'selected' : '' }}>Uang
                                </option>
                                <option value="trofi" {{ in_array('trofi', old('hadiah', [])) ? 'selected' : '' }}>Trofi
                                </option>
                                <option value="sertifikat" {{ in_array('sertifikat', old('hadiah', [])) ? 'selected' : '' }}>
                                    Sertifikat</option>
                            </select>
                            <small id="error-hadiah" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" id="formHadiah" style="{{ in_array('uang', old('hadiah', [])) ? '' : 'display: none;' }}">
                            <label class="font-weight-bold text-muted">Jumlah Hadiah Uang</label>
                            <div class="input-group">
                                {!! $inputIcon('money-bill-wave') !!}
                                <input type="number" name="hadiah_uang" class="form-control border-left-0" placeholder="Masukkan total hadiah (Rp)" value="{{ old('hadiah_uang') }}">
                            </div>
                            <small id="error-hadiah_uang" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Jenis Biaya --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Jenis Biaya</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_biaya" id="jenisGratis" value="gratis" {{ old('jenis_biaya') == 'gratis' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenisGratis">Gratis</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_biaya" id="jenisBerbayar" value="berbayar" {{ old('jenis_biaya') == 'berbayar' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenisBerbayar">Berbayar</label>
                            </div>
                        </div>
                    </div>

                    {{-- Jenis Pendaftaran --}}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Jenis Pendaftaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_pendaftaran" id="jenisIndividu" value="individu" {{ old('jenis_pendaftaran') == 'individu' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenisIndividu">Individu</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_pendaftaran" id="jenisTim" value="tim" {{ old('jenis_pendaftaran') == 'tim' ? 'checked' : '' }}>
                                <label class="form-check-label" for="jenisTim">Tim</label>
                            </div>
                            <small id="error-jenis_pendaftaran" class="form-text text-danger error-text"></small>
                        </div>
                    </div>
                </div>

                {{-- Harga Pendaftaran --}}
                <div class="col-md-12">
                    <div class="form-group" id="formHarga" style="{{ old('jenis_biaya') == 'berbayar' ? '' : 'display: none;' }}">
                        <label class="font-weight-bold text-muted">Harga Pendaftaran</label>
                        <div class="input-group">
                            {!! $inputIcon('money-bill-wave') !!}
                            <input type="number" name="harga_pendaftaran" class="form-control border-left-0" placeholder="Masukkan harga (Rp)" value="{{ old('harga_pendaftaran') }}">
                        </div>
                        <small id="error-harga_pendaftaran" class="form-text text-danger error-text"></small>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="font-weight-bold text-muted">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi lomba" required>{{ old('deskripsi') }}</textarea>
                    <small id="error-deskripsi" class="form-text text-danger error-text"></small>
                </div>

                @if (auth()->user()->level->level_code === 'DSN')
                    {{-- Status Verifikasi --}}
                    <div class="form-group">
                        <label class="font-weight-bold text-muted">Status Verifikasi</label>
                        <select name="status_verifikasi" class="form-control" required>
                            <option value="pending" {{ old('status_verifikasi') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="verified" {{ old('status_verifikasi') == 'verified' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="rejected" {{ old('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                        <small id="error-status_verifikasi" class="form-text text-danger error-text"></small>
                    </div>
                @else
                    <input type="hidden" name="status_verifikasi" value="verified">
                @endif

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
                    $('#formHarga').slideDown();
                } else {
                    $('#formHarga').slideUp();
                }
            });

            $('input[name="jenis_pendaftaran"]').on('change', function() {
                if ($(this).val() === 'berbayar') {
                    $('#formHarga').show();
                } else {
                    $('#formHarga').hide();
                }
            });

            function toggleHadiahUang() {
                const selected = $('#hadiahSelect').val() || [];

                if (selected.includes('uang')) {
                    $('#formHadiah').slideDown();
                } else {
                    $('#formHadiah').slideUp();
                }
            }

            toggleHadiahUang();

            $('#hadiahSelect').on('change', function() {
                toggleHadiahUang();
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
