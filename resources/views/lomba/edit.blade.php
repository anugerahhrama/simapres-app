@extends('layouts.app')

@section('title', 'Edit Lomba')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h4>Edit Lomba</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('lomba.update', $lomba->id) }}" method="POST" id="form-edit">
                    @csrf
                    @method('PUT')

                    @php
                        $inputIcon = fn($icon) => "<div class='input-group-prepend'><span class='input-group-text bg-light'><i class='fas fa-$icon'></i></span></div>";
                    @endphp

                    {{-- Judul --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Lomba</label>
                        <div class="input-group">
                            {!! $inputIcon('book') !!}
                            <input type="text" name="judul" class="form-control border-left-0" value="{{ old('judul', $lomba->judul) }}" required>
                        </div>
                        <small class="text-danger">{{ $errors->first('judul') }}</small>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Kategori</label>
                        <div class="input-group">
                            {!! $inputIcon('tags') !!}
                            <select name="kategori" class="form-control border-left-0" required>
                                <option disabled>-- Pilih Kategori --</option>
                                <option value="akademik" {{ old('kategori', $lomba->kategori) == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="non akademik" {{ old('kategori', $lomba->kategori) == 'non akademik' ? 'selected' : '' }}>Non Akademik</option>
                            </select>
                        </div>
                        <small class="text-danger">{{ $errors->first('kategori') }}</small>
                    </div>

                    {{-- Tingkatan --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Tingkatan</label>
                        <div class="input-group">
                            {!! $inputIcon('layer-group') !!}
                            <select name="tingkatan_lomba_id" class="form-control border-left-0" required>
                                <option disabled>-- Pilih Tingkatan --</option>
                                @foreach ($tingkatanLombas as $tingkatan)
                                    <option value="{{ $tingkatan->id }}" {{ old('tingkatan_lomba_id', $lomba->tingkatan_lomba_id) == $tingkatan->id ? 'selected' : '' }}>
                                        {{ ucfirst($tingkatan->nama) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{ $errors->first('tingkatan_lomba_id') }}</small>
                    </div>

                    {{-- Penyelenggara --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Penyelenggara</label>
                        <div class="input-group">
                            {!! $inputIcon('building') !!}
                            <input type="text" name="penyelenggara" class="form-control border-left-0" value="{{ old('penyelenggara', $lomba->penyelenggara) }}" required>
                        </div>
                        <small class="text-danger">{{ $errors->first('penyelenggara') }}</small>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $lomba->deskripsi) }}</textarea>
                        <small class="text-danger">{{ $errors->first('deskripsi') }}</small>
                    </div>

                    {{-- Link --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Link Registrasi</label>
                        <div class="input-group">
                            {!! $inputIcon('link') !!}
                            <input type="url" name="link_registrasi" class="form-control border-left-0" value="{{ old('link_registrasi', $lomba->link_registrasi) }}" required>
                        </div>
                        <small class="text-danger">{{ $errors->first('link_registrasi') }}</small>
                    </div>

                    {{-- Tanggal --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Awal Registrasi</label>
                        <div class="input-group">
                            {!! $inputIcon('calendar-alt') !!}
                            <input type="date" name="awal_registrasi" class="form-control border-left-0" value="{{ old('awal_registrasi', $lomba->awal_registrasi) }}" required>
                        </div>
                        <small class="text-danger">{{ $errors->first('awal_registrasi') }}</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Akhir Registrasi</label>
                        <div class="input-group">
                            {!! $inputIcon('calendar-check') !!}
                            <input type="date" name="akhir_registrasi" class="form-control border-left-0" value="{{ old('akhir_registrasi', $lomba->akhir_registrasi) }}" required>
                        </div>
                        <small class="text-danger">{{ $errors->first('akhir_registrasi') }}</small>
                    </div>

                    {{-- Keahlian --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Bidang Keahlian</label>
                        <select class="form-control select2bs4" name="keahlian[]" multiple="multiple" required>
                            @foreach ($keahlians as $keahlian)
                                <option value="{{ $keahlian->id }}" {{ in_array($keahlian->id, old('keahlian', $lomba->keahlian->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $keahlian->nama_keahlian }}
                                </option>
                            @endforeach

                            {{-- Tambahkan opsi baru yang diketik manual --}}
                            @foreach (old('keahlian', []) as $item)
                                @if (!is_numeric($item))
                                    <option value="{{ $item }}" selected>{{ $item }}</option>
                                @endif
                            @endforeach
                        </select>
                        <small class="text-danger">{{ $errors->first('keahlian') }}</small>
                    </div>

                    {{-- Jenis Pendaftaran --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Jenis Pendaftaran</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_pendaftaran" value="gratis" {{ old('jenis_pendaftaran', $lomba->jenis_pendaftaran) === 'gratis' ? 'checked' : '' }}>
                            <label class="form-check-label">Gratis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_pendaftaran" value="berbayar" {{ old('jenis_pendaftaran', $lomba->jenis_pendaftaran) === 'berbayar' ? 'checked' : '' }}>
                            <label class="form-check-label">Berbayar</label>
                        </div>
                        <small class="text-danger">{{ $errors->first('jenis_pendaftaran') }}</small>
                    </div>

                    {{-- Harga Pendaftaran --}}
                    <div class="form-group" id="formHarga" style="{{ old('jenis_pendaftaran', $lomba->jenis_pendaftaran) === 'berbayar' ? '' : 'display: none;' }}">
                        <label class="font-weight-bold">Harga Pendaftaran</label>
                        <input type="number" name="harga_pendaftaran" class="form-control" value="{{ old('harga_pendaftaran', $lomba->harga_pendaftaran) }}">
                        <small class="text-danger">{{ $errors->first('harga_pendaftaran') }}</small>
                    </div>

                    {{-- Uang dan Sertifikat --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Mendapatkan Uang?</label>
                        <select name="mendapatkan_uang" class="form-control" required>
                            <option value="1" {{ old('mendapatkan_uang', $lomba->mendapatkan_uang) == 1 ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ old('mendapatkan_uang', $lomba->mendapatkan_uang) == 0 ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Mendapatkan Sertifikat?</label>
                        <select name="mendapatkan_sertifikat" class="form-control" required>
                            <option value="1" {{ old('mendapatkan_sertifikat', $lomba->mendapatkan_sertifikat) == 1 ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ old('mendapatkan_sertifikat', $lomba->mendapatkan_sertifikat) == 0 ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>

                    {{-- Verifikasi --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Status Verifikasi</label>
                        <select name="status_verifikasi" class="form-control" required>
                            <option value="pending" {{ old('status_verifikasi', $lomba->status_verifikasi) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ old('status_verifikasi', $lomba->status_verifikasi) === 'verified' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ old('status_verifikasi', $lomba->status_verifikasi) === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        <small class="text-danger">{{ $errors->first('status_verifikasi') }}</small>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('lomba.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                tags: true,
                tokenSeparators: [',']
            });

            $('input[name="jenis_pendaftaran"]').on('change', function() {
                if ($(this).val() === 'berbayar') {
                    $('#formHarga').slideDown();
                } else {
                    $('#formHarga').slideUp();
                }
            });
        });
    </script>
@endpush
