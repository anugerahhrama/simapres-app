@extends('layouts.app')

@section('content')
<div class="card mt-4">
    <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom">
            <h4 class="mb-0">Monitoring</h4>
        </div>
        <div class="row text-center mt-3 mb-2">
            <div class="col-md-3 col-6 mb-2">
                <div class="bg-info rounded p-3 text-white">
                    <div class="h2 mb-0">{{ $totalLombaAktif ?? 0 }}</div>
                    <div>Lomba Aktif</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="bg-success rounded p-3 text-white">
                    <div class="h2 mb-0">{{ $dokumenDiunggah->count() ?? 0 }}</div>
                    <div>Dokumen Terunggah</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="bg-warning rounded p-3 text-white">
                    <div class="h2 mb-0">{{ $totalSertifikat ?? 0 }}</div>
                    <div>Sertifikat</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <div class="bg-danger rounded p-3 text-white">
                    <div class="h2 mb-0">{{ $totalEvaluasi ?? 0 }}</div>
                    <div>Evaluasi</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        @if (session('success'))
            <div class="alert alert-success mx-2 mt-2" style="border-radius: 6px;">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mx-2 mt-2" style="border-radius: 6px;">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="row m-0">
            <div class="col-md-8 p-2">
                {{-- Lomba yang Diikuti --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Lomba yang Diikuti</strong>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lomba</th>
                                        <th>Penyelenggara</th>
                                        <th>Kategori</th>
                                        <th>Pencapaian</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftarPrestasi ?? [] as $index => $prestasi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $prestasi->nama_lomba }}</td>
                                            <td>{{ $prestasi->penyelenggara }}</td>
                                            <td>{{ $prestasi->kategori }}</td>
                                            <td>{{ $prestasi->pencapaian }}</td>
                                            <td>
                                                @if ($prestasi->status_verifikasi == 'verified')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @elseif ($prestasi->status_verifikasi == 'rejected')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @else
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada lomba/prestasi yang diinput.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Dokumen yang Diunggah --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Dokumen yang Diunggah</strong>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Dokumen</th>
                                        <th>Tanggal Diunggah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dokumenDiunggah ?? [] as $index => $dokumen)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $dokumen->jenis_dokumen ?? '-' }}</td>
                                            <td>
                                                {{ isset($dokumen->tanggal_upload) ? \Carbon\Carbon::parse($dokumen->tanggal_upload)->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                @if ($dokumen->status_verifikasi == 'Disetujui')
                                                    <span class="badge badge-success">Disetujui</span>
                                                @elseif ($dokumen->status_verifikasi == 'Menunggu')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif ($dokumen->status_verifikasi == 'Ditolak')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $dokumen->status_verifikasi ?? '-' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada dokumen yang diunggah.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Sertifikat Prestasi --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Sertifikat Prestasi</strong>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Lomba</th>
                                        <th>Nama File</th>
                                        <th>Tanggal Upload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($sertifikatList as $sertifikat)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $sertifikat->prestasi->nama_lomba ?? '-' }}</td>
                                            <td>{{ $sertifikat->nama_file }}</td>
                                            <td>{{ $sertifikat->tanggal_upload ? \Carbon\Carbon::parse($sertifikat->tanggal_upload)->format('d M Y') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada sertifikat diunggah.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-2">
                {{-- Log Aktivitas Prestasi --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Log Aktivitas Prestasi</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-unbordered mb-3">
                            @php
                                $logPrestasi = $daftarPrestasi->sortByDesc('created_at')->take(10);
                            @endphp
                            @forelse($logPrestasi as $log)
                                <li class="list-group-item">
                                    <div>
                                        <strong>{{ $log->nama_lomba }}</strong>
                                        <span class="text-muted float-right" style="font-size: 12px;">
                                            {{ $log->created_at ? $log->created_at->format('d M Y H:i') : '-' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge badge-info">{{ $log->kategori }}</span>
                                        <span class="ml-2">{{ $log->pencapaian }}</span>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">
                                    Belum ada aktivitas prestasi.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('monitoring.cetak') }}" target="_blank" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Cetak Hasil Log Aktivitas
            </a>
        </div>
    </div>
</div>
@endsection
