@extends('layouts.app') {{-- Asumsi Anda memiliki layout utama bernama 'layouts.app' --}}

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Edit Prestasi</h4>
    </div>
    <div class="card-body">
        {{-- Form untuk mengedit prestasi --}}
        {{-- Menggunakan route('prestasi.update') karena itu adalah rute default untuk update resource --}}
        <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Penting: Menggunakan metode PUT untuk update --}}

            {{-- Mahasiswa (Dropdown) --}}
            <div class="form-group row">
                <label for="mahasiswa_id" class="col-sm-2 col-form-label">Mahasiswa</label>
                <div class="col-sm-10">
                    <select class="form-control @error('mahasiswa_id') is-invalid @enderror" id="mahasiswa_id" name="mahasiswa_id" required>
                        <option value="">- Pilih Mahasiswa -</option>
                        {{-- Iterasi melalui $mahasiswas yang dikirim dari controller --}}
                        {{-- Asumsi model User memiliki properti 'name' dan 'id' --}}
                        @foreach($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ (old('mahasiswa_id', $prestasi->mahasiswa_id) == $mahasiswa->id) ? 'selected' : '' }}>
                                {{ $mahasiswa->name }} ({{ $mahasiswa->email }}) {{-- Tampilkan nama dan email untuk kejelasan --}}
                            </option>
                        @endforeach
                    </select>
                    @error('mahasiswa_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Lomba (Dropdown) --}}
            <div class="form-group row">
                <label for="lomba_id" class="col-sm-2 col-form-label">Lomba</label>
                <div class="col-sm-10">
                    <select class="form-control @error('lomba_id') is-invalid @enderror" id="lomba_id" name="lomba_id" required>
                        <option value="">- Pilih Lomba -</option>
                        {{-- Iterasi melalui $lombas yang dikirim dari controller --}}
                        {{-- Asumsi model Lomba memiliki properti 'judul', 'penyelenggara', dan 'id' --}}
                        @foreach($lombas as $lomba)
                            <option value="{{ $lomba->id }}" {{ (old('lomba_id', $prestasi->lomba_id) == $lomba->id) ? 'selected' : '' }}>
                                {{ $lomba->judul }} - {{ $lomba->penyelenggara }}
                            </option>
                        @endforeach
                    </select>
                    @error('lomba_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Nama Kegiatan --}}
            <div class="form-group row">
                <label for="nama_kegiatan" class="col-sm-2 col-form-label">Nama Kegiatan</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required>
                    @error('nama_kegiatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="form-group row">
                <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea> {{-- Deskripsi nullable --}}
                    @error('deskripsi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="form-group row">
                <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($prestasi->tanggal)->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Kategori --}}
            <div class="form-group row">
                <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select class="form-control @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="Akademik" {{ (old('kategori', $prestasi->kategori) == 'Akademik') ? 'selected' : '' }}>Akademik</option>
                        <option value="Non Akademik" {{ (old('kategori', $prestasi->kategori) == 'Non Akademik') ? 'selected' : '' }}>Non Akademik</option>
                    </select>
                    @error('kategori')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Pencapaian --}}
            <div class="form-group row">
                <label for="pencapaian" class="col-sm-2 col-form-label">Pencapaian</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('pencapaian') is-invalid @enderror" id="pencapaian" name="pencapaian" value="{{ old('pencapaian', $prestasi->pencapaian) }}" required>
                    @error('pencapaian')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Evaluasi Diri --}}
            <div class="form-group row">
                <label for="evaluasi_diri" class="col-sm-2 col-form-label">Evaluasi Diri (Opsional)</label>
                <div class="col-sm-10">
                    <textarea class="form-control @error('evaluasi_diri') is-invalid @enderror" id="evaluasi_diri" name="evaluasi_diri" rows="3">{{ old('evaluasi_diri', $prestasi->evaluasi_diri) }}</textarea>
                    @error('evaluasi_diri')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Status Verifikasi --}}
            <div class="form-group row">
                <label for="status_verifikasi" class="col-sm-2 col-form-label">Status Verifikasi</label>
                <div class="col-sm-10">
                    <select class="form-control @error('status_verifikasi') is-invalid @enderror" id="status_verifikasi" name="status_verifikasi" required>
                        <option value="pending" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'pending') ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'approved') ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ (old('status_verifikasi', $prestasi->status_verifikasi) == 'rejected') ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status_verifikasi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    {{-- Tombol Batal untuk menutup modal, sama seperti di index.blade.php --}}
                    <button type="button" class="btn btn-secondary btn-sm" onclick="$('#myModal').modal('hide')">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
