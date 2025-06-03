@extends('layouts.app')
@section('content')
    <form action="{{ route('prestasi.store') }}" method="POST" id="form-tambah-prestasi-modal" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="prestasiModalLabel">Tambah Data Prestasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="text" name="mahasiswa_id" id="user_id" value="{{ Auth::user()->id }}" hidden class="form-control">
        <div class="form-group">
            <label>Nama Lomba</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
            <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" required>
            <small id="error-kategori" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Deskripsi (Opsional)</label> 
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
            <small id="error-deskripsi" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            <small id="error-tanggal" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Pencapaian</label>
            <input type="text" name="pencapaian" id="pencapaian" class="form-control" required>
            <small id="error-pencapaian" class="error-text form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label>Evaluasi Diri (Opsional)</label> {{-- Tambahkan (Opsional) jika memang begitu --}}
            <textarea name="evaluasi_diri" id="evaluasi_diri" class="form-control"></textarea>
            <small id="error-evaluasi_diri" class="error-text form-text text-danger"></small>
        </div>
        <input type="hidden" name="status_verifikasi" value="pending">
        <input type="file" name="bukti" id="">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
@endsection