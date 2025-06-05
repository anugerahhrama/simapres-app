@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg" style="border-radius: 10px;">
                <div class="card-body p-4">
                    <form action="{{ route('lomba.update', $lomba->id) }}" method="POST" id="form-edit">
                        @csrf
                        @method('PUT')

                        @php
                            $inputIcon = fn($icon) => "<span class='input-group-text bg-light'><i class='fas fa-$icon'></i></span>";
                        @endphp

                        <div class="form-group">
                            <label class="font-weight-bold">Judul Lomba</label>
                            <div class="input-group">
                                {!! $inputIcon('book') !!}
                                <input type="text" name="judul" value="{{ $lomba->judul }}" class="form-control border-left-0" placeholder="Masukkan judul lomba" required>
                            </div>
                            <small id="error-judul" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Kategori</label>
                            <div class="input-group">
                                {!! $inputIcon('tags') !!}
                                <select name="kategori" class="form-control border-left-0" required>
                                    <option value="" disabled {{ !$lomba->kategori ? 'selected' : '' }}>-- Pilih Kategori --</option>
                                    <option value="akademik" {{ $lomba->kategori == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="non akademik" {{ $lomba->kategori == 'non akademik' ? 'selected' : '' }}>Non Akademik</option>
                                </select>
                            </div>
                            <small id="error-kategori" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tingkatan</label>
                            <div class="input-group">
                                {!! $inputIcon('layer-group') !!}
                                <select name="tingkatan" class="form-control border-left-0" required>
                                    <option value="" disabled {{ !$lomba->tingkatan ? 'selected' : '' }}>-- Pilih Tingkatan --</option>
                                    @foreach(['pemula', 'lokal', 'regional', 'nasional', 'internasional'] as $tingkatan)
                                        <option value="{{ $tingkatan }}" {{ $lomba->tingkatan == $tingkatan ? 'selected' : '' }}>{{ ucfirst($tingkatan) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small id="error-tingkatan" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Penyelenggara</label>
                            <div class="input-group">
                                {!! $inputIcon('building') !!}
                                <input type="text" name="penyelenggara" value="{{ $lomba->penyelenggara }}" class="form-control border-left-0" placeholder="Masukkan penyelenggara lomba">
                            </div>
                            <small id="error-penyelenggara" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Masukkan deskripsi lomba">{{ $lomba->deskripsi }}</textarea>
                            <small id="error-deskripsi" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Link Registrasi</label>
                            <div class="input-group">
                                {!! $inputIcon('link') !!}
                                <input type="url" name="link_registrasi" value="{{ $lomba->link_registrasi }}" class="form-control border-left-0" placeholder="https://contoh.com">
                            </div>
                            <small id="error-link_registrasi" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Awal Registrasi</label>
                            <div class="input-group">
                                {!! $inputIcon('calendar-alt') !!}
                                <input type="date" name="awal_registrasi" value="{{ $lomba->awal_registrasi }}" class="form-control border-left-0">
                            </div>
                            <small id="error-awal_registrasi" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Akhir Registrasi</label>
                            <div class="input-group">
                                {!! $inputIcon('calendar-check') !!}
                                <input type="date" name="akhir_registrasi" value="{{ $lomba->akhir_registrasi }}" class="form-control border-left-0">
                            </div>
                            <small id="error-akhir_registrasi" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Bidang Keahlian</label>
                            <select name="bidang_keahlian_id" class="form-control">
                                @foreach($keahlians as $keahlian)
                                    <option value="{{ $keahlian->id }}" {{ $lomba->keahlian_id == $keahlian->id ? 'selected' : '' }}>{{ $keahlian->nama_keahlian }}</option>
                                @endforeach
                            </select>
                            <small id="error-keahlian_id" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Minat</label>
                            <select name="minat_id" class="form-control">
                                @foreach($minats as $minat)
                                    <option value="{{ $minat->id }}" {{ $lomba->minat_id == $minat->id ? 'selected' : '' }}>{{ $minat->nama_minat }}</option>
                                @endforeach
                            </select>
                            <small id="error-minat_id" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Jenis Pendaftaran</label>
                            <input type="text" name="jenis_pendaftaran" value="{{ $lomba->jenis_pendaftaran }}" class="form-control">
                            <small id="error-jenis_pendaftaran" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Harga Pendaftaran</label>
                            <input type="number" name="harga_pendaftaran" value="{{ $lomba->harga_pendaftaran }}" class="form-control">
                            <small id="error-harga_pendaftaran" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Perkiraan Hadiah</label>
                            <input type="text" name="perkiraan_hadiah" value="{{ $lomba->perkiraan_hadiah }}" class="form-control">
                            <small id="error-perkiraan_hadiah" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mendapatkan Uang?</label>
                            <select name="mendapatkan_uang" class="form-control">
                                <option value="1" {{ $lomba->mendapatkan_uang ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ !$lomba->mendapatkan_uang ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mendapatkan Sertifikat?</label>
                            <select name="mendapatkan_sertifikat" class="form-control">
                                <option value="1" {{ $lomba->mendapatkan_sertifikat ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ !$lomba->mendapatkan_sertifikat ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nilai Benefit</label>
                            <input type="number" step="0.01" name="nilai_benefit" value="{{ $lomba->nilai_benefit }}" class="form-control">
                            <small id="error-nilai_benefit" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-control">
                                <option value="pending" {{ $lomba->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ $lomba->status_verifikasi == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ $lomba->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <small id="error-status_verifikasi" class="form-text text-danger error-text"></small>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('lomba.index') }}" class="btn btn-light border shadow-sm mr-2">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-warning shadow-sm">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
