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
                        <div class="h2 mb-0">{{ $totalLombaAktif }}</div>
                        <div>Lomba Aktif</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="bg-success rounded p-3 text-white">
                        <div class="h2 mb-0">{{ $totalDokumenTertunda }}</div>
                        <div>Dokumen Tertunda</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="bg-warning rounded p-3 text-white">
                        <div class="h2 mb-0">{{ $totalSertifikat }}</div>
                        <div>Sertifikat</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="bg-danger rounded p-3 text-white">
                        <div class="h2 mb-0">{{ $totalEvaluasi }}</div>
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
                                            <th>Judul Lomba</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                            <th>Label</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pendaftaranLombas as $index => $pendaftaran)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $pendaftaran->lomba->judul ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($pendaftaran->status == 'Aktif')
                                                        <span class="badge badge-success">Aktif</span>
                                                    @elseif ($pendaftaran->status == 'Selesai')
                                                        <span class="badge badge-primary">Selesai</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $pendaftaran->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="progress progress-xs" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ $pendaftaran->progress ?? 0 }}%"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">{{ $pendaftaran->label ?? '0%' }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada lomba yang diikuti.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
                                        @forelse ($dokumenDiunggah as $index => $dokumen)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $dokumen->jenis_dokumen }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dokumen->tanggal_upload)->format('d M Y') }}</td>
                                                <td>
                                                    @if ($dokumen->status_verifikasi == 'Disetujui')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif ($dokumen->status_verifikasi == 'Menunggu')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @else
                                                        <span class="badge badge-secondary">{{ $dokumen->status_verifikasi }}</span>
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
                </div>
                <div class="col-md-4 p-2">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Log Aktivitas</strong>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item text-center text-muted">
                                    Log aktivitas belum tersedia.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
