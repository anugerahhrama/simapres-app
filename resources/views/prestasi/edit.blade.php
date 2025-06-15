@extends('layouts.app')

@section('title', 'Edit Prestasi')

@section('content')
    <div class="card border-0 shadow-lg my-4" style="border-radius: 10px;">
        <div class="card-body p-4">
            <form action="{{ route('prestasi.update', $prestasi->id) }}" method="POST" id="form-edit" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                <input type="text" name="nama_lomba" class="form-control border-left-0" placeholder="Masukkan nama lomba" value="{{ old('nama_lomba', $prestasi->nama_lomba) }}" required>
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
                                <input type="text" name="penyelenggara" class="form-control border-left-0" placeholder="Nama institusi penyelenggara" value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required>
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
                                    <option value="" disabled>-Pilih Kategori-</option>
                                    @foreach ($tingkatan as $item)
                                        <option value="{{ $item->nama }}" {{ old('kategori', $prestasi->kategori) == $item->nama ? 'selected' : '' }}>{{ $item->nama }}</option>
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
                                <input type="date" name="tanggal" class="form-control border-left-0" value="{{ old('tanggal', $prestasi->tanggal) }}" required>
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
                                <input type="text" name="pencapaian" class="form-control border-left-0" placeholder="Juara 1, Finalis, dll." value="{{ old('pencapaian', $prestasi->pencapaian) }}" required>
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
                                <textarea name="deskripsi" class="form-control border-left-0" placeholder="Deskripsi singkat kegiatan">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
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
                                <textarea name="evaluasi_diri" class="form-control border-left-0" placeholder="Apa yang kamu pelajari dari lomba ini?">{{ old('evaluasi_diri', $prestasi->evaluasi_diri) }}</textarea>
                            </div>
                            <small id="error-evaluasi_diri" class="form-text text-danger error-text"></small>
                        </div>
                    </div>

                    {{-- File Bukti Lama --}}
                    @if ($prestasi->bukti && $prestasi->bukti->count() > 0)
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">File Bukti Sebelumnya</label>
                            <ul class="list-group">
                                @foreach ($prestasi->bukti as $file)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ Storage::url($file->path_file) }}" target="_blank">
                                            <i class="fas fa-file mr-2"></i>{{ $file->nama_file }}
                                        </a>
                                        <span class="badge badge-success">Tersimpan</span>
                                    </li>
                                @endforeach
                            </ul>
                            <small class="form-text text-muted mt-2">
                                File ini telah diupload sebelumnya. Kamu dapat menambahkan file baru di bawah.
                            </small>
                        </div>
                    @endif


                    {{-- Upload Bukti Baru --}}
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted mb-2">Upload Bukti Prestasi (Tambahan)</label>
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
                    <input type="hidden" name="status_verifikasi" value="{{ $prestasi->status_verifikasi }}">
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('prestasi.index') }}" class="btn btn-light border shadow-sm mr-2">
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
