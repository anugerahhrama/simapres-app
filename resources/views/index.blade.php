@extends('layouts.app')

@section('content')
    @if (auth()->user()->level->level_code === 'ADM')
        <!-- Admin Dashboard -->
        <div class="dashboard-container">
            <!-- Welcome Section -->
            <div class="welcome-section mb-4">
                <h2 class="welcome-title">Selamat Datang, {{ auth()->user()->detailUser->name }}</h2>
                <p class="welcome-subtitle">Panel Admin SIMAPRES</p>
            </div>

            <!-- Stats Cards Row -->
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stat-header">
                            <i class="fas fa-users text-dark mr-2"></i>
                            <span class="stat-title">Total Pengguna</span>
                        </div>
                        <div class="stat-value">{{ $totalUsers ?? '0' }}</div>
                        <div class="stat-subtitle">Total pengguna terdaftar</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stat-header">
                            <i class="fas fa-graduation-cap text-dark mr-2"></i>
                            <span class="stat-title">Total Program Studi</span>
                        </div>
                        <div class="stat-value">{{ $totalProdis ?? '0' }}</div>
                        <div class="stat-subtitle">Total program studi terdaftar</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stat-header">
                            <i class="fas fa-trophy text-dark mr-2"></i>
                            <span class="stat-title">Total Lomba</span>
                        </div>
                        <div class="stat-value">{{ $totalLomba ?? '0' }}</div>
                        <div class="stat-subtitle">Total lomba terdaftar</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="stats-card">
                        <div class="stat-header">
                            <i class="fas fa-medal text-dark mr-2"></i>
                            <span class="stat-title">Total Prestasi</span>
                        </div>
                        <div class="stat-value">{{ $totalPrestasi ?? '0' }}</div>
                        <div class="stat-subtitle">Total prestasi terdaftar</div>
                    </div>
                </div>
            </div>

            <!-- Main Content Row -->
            <div class="row">
                <!-- Left Column -->
                <section class="col-lg-7 connectedSortable">
                    <!-- Prestasi Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-trophy mr-1"></i>
                                Statistik Prestasi
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#prestasi-chart" data-toggle="tab">Bulanan</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#prestasi-trend" data-toggle="tab">Tahunan</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <div class="chart tab-pane active" id="prestasi-chart" style="position: relative; height: 500px;">
                                    <div class="mb-3">
                                        <form action="{{ route('dashboard') }}" method="GET" class="form-inline">
                                            <div class="form-group">
                                                <label for="tahun" class="mr-2">Tahun:</label>
                                                <select name="tahun" id="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                                                    @foreach ($tahunTersedia as $tahun)
                                                        <option value="{{ $tahun }}" {{ $tahun == $tahunSekarang ? 'selected' : '' }}>
                                                            {{ $tahun }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <canvas id="prestasi-chart-canvas" height="500" style="height: 500px;"></canvas>
                                </div>
                                <div class="chart tab-pane" id="prestasi-trend" style="position: relative; height: 500px;">
                                    <canvas id="prestasi-trend-canvas" height="500" style="height: 500px;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                Butuh Verifikasi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <!-- Verifikasi Lomba -->
                                @forelse($butuhVerifikasi as $lomba)
                                    <div class="time-label">
                                        <span class="bg-warning">{{ $lomba->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-trophy bg-info"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i>
                                                {{ $lomba->created_at->format('H:i') }}</span>
                                            <h3 class="timeline-header">
                                                Nama Lomba: {{ $lomba->judul }}
                                                <br>
                                                Penyelenggara: {{ $lomba->penyelenggara }}
                                            </h3>
                                            <h3 class="timeline-header">
                                                Dibuat Oleh: {{ $lomba->createdBy->detailUser->name }}
                                            </h3>
                                            <div class="timeline-body">
                                                <a href="{{ route('verifLomba.index') }}" class="btn btn-sm btn-primary">
                                                    Verifikasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-3">
                                        <p class="text-muted">Tidak ada lomba yang perlu diverifikasi</p>
                                    </div>
                                @endforelse

                                <!-- Verifikasi Prestasi -->
                                @forelse($butuhVerifikasiPrestasi as $prestasi)
                                    <div class="time-label">
                                        <span class="bg-warning">{{ $prestasi->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-medal bg-success"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i>
                                                {{ $prestasi->created_at->format('H:i') }}</span>
                                            <h3 class="timeline-header">
                                                Nama Prestasi: {{ $prestasi->nama_lomba }}
                                            </h3>
                                            <h3 class="timeline-header">
                                                Mahasiswa: {{ $prestasi->user->detailUser->name }}
                                            </h3>
                                            <div class="timeline-body">
                                                <a href="{{ route('verifPres.index') }}" class="btn btn-sm btn-primary">
                                                    Verifikasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center p-3">
                                        <p class="text-muted">Tidak ada prestasi yang perlu diverifikasi</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Right Column -->
                <section class="col-lg-5 connectedSortable">
                    <!-- Upcoming Events -->
                    <div class="card bg-gradient-primary">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Jadwal Lomba Aktif
                            </h3>
                        </div>
                        <div class="card-body p-2">
                            <div class="space-y-2">
                                @forelse($upcomingEvents as $event)
                                    @if ($event->status_verifikasi === 'verified')
                                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 hover:bg-white/20 transition-all">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-14 h-14 bg-white/20 rounded-xl flex flex-col items-center justify-center">
                                                    <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->awal_registrasi)->format('d') }}</span>
                                                    <span class="text-sm">{{ \Carbon\Carbon::parse($event->awal_registrasi)->format('M') }}</span>
                                                </div>
                                                <div class="flex-grow">
                                                    <h5 class="text-base font-semibold mb-0.5">{{ $event->nama_lomba }}
                                                    </h5>
                                                    <p class="text-white/80 text-sm mb-1">{{ Str::limit($event->judul) }}
                                                    </p>
                                                    <div class="flex flex-wrap gap-4 text-sm text-white/70">
                                                        <span class="flex items-center gap-1">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{ $event->penyelenggara }}
                                                        </span>
                                                        <span class="flex items-center gap-1 ml-2">
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($event->awal_registrasi)->format('d M Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($event->akhir_registrasi)->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                    <button type="button" class="mt-2 px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm transition-all" data-toggle="modal" data-target="#modal-detail-{{ $event->id }}">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </button>

                                                    <div class="modal fade" id="modal-detail-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-detail-label-{{ $event->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-body">
                                                                @include('lomba.show', ['lomba' => $event])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-3">
                                        <p class="text-white/70 text-sm">Tidak ada jadwal lomba aktif</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Prestasi Terbaru -->
                    <div class="card">
                        <div class="card-header bg-gradient-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-medal mr-1"></i>
                                Prestasi Terbaru
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($prestasiTerbaru as $prestasi)
                                    @if ($prestasi->status_verifikasi === 'verified')
                                        <div class="list-group-item border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0" style="margin-left: 1rem;">
                                                    @if ($prestasi->user->detailUser->photo_file)
                                                        <img src="{{ asset('storage/' . $prestasi->user->detailUser->photo_file) }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" alt="User Avatar">
                                                    @else
                                                        <img class="rounded-circle" style="width: 50px; height: 50px;" src="https://ui-avatars.com/api/?name={{ urlencode(optional($prestasi->user->detailUser)->name ?? 'User') }}&background=667eea&color=fff" alt="user photo">
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1" style="margin-left: 1rem;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 fw-bold">{{ $prestasi->user->detailUser->name }}
                                                        </h6>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($prestasi->tanggal)->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-1 text-muted">{{ $prestasi->pencapaian }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-trophy text-warning me-2"></i>
                                                        <span class="text-muted">{{ $prestasi->nama_lomba }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-4">
                                        <p class="text-muted mb-0">Belum ada prestasi terbaru</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @elseif(auth()->user()->level->level_code === 'MHS')
        <!-- Mahasiswa Dashboard -->
        <div class="dashboard-container">
            <div class="welcome-section mb-4">
                <h2 class="welcome-title">Selamat Datang, {{ auth()->user()->detailUser->name }}</h2>
                <p class="welcome-subtitle">Portal Mahasiswa SIMAPRES</p>
            </div>

            <!-- Recent Achievements -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Prestasi Saya
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Prestasi</th>
                                    <th>Pencapaian</th>
                                    <th>Penyelenggara</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prestasiTerbaru as $prestasi)
                                    <tr>
                                        <td>{{ $prestasi->nama_lomba }}</td>
                                        <td>{{ $prestasi->pencapaian }}</td>
                                        <td>{{ $prestasi->penyelenggara }}</td>
                                        <td>{{ $prestasi->tanggal }}</td>
                                        <td>
                                            <span class="badge bg-{{ $prestasi->status_verifikasi === 'verified' ? 'success' : ($prestasi->status_verifikasi === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($prestasi->status_verifikasi) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('prestasi.index', $prestasi->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada prestasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Lomba Aktif -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-flag me-2"></i>
                        Lomba Aktif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Lomba</th>
                                    <th>Penyelenggara</th>
                                    <th>Tingkat</th>
                                    <th>Kategori</th>
                                    <th>Jenis Pendaftaran</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lombaTerdaftar as $lomba)
                                    <tr>
                                        <td>{{ $lomba->judul }}</td>
                                        <td>{{ $lomba->penyelenggara }}</td>
                                        <td>{{ $lomba->tingkatanLomba->nama }}</td>
                                        <td>{{ $lomba->kategori }}</td>
                                        <td>{{ $lomba->jenis_pendaftaran }}</td>
                                        <td>{{ $lomba->awal_registrasi }}</td>
                                        <td>{{ $lomba->akhir_registrasi }}</td>
                                        <td>
                                            <a href="{{ route('rekomendasi.lomba') }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada lomba yang terdaftar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @elseif(auth()->user()->level->level_code === 'DSN')
        <!-- Dosen Dashboard -->
        <div class="dashboard-container">
            <div class="welcome-section mb-4">
                <h2 class="welcome-title">Selamat Datang, {{ auth()->user()->detailUser->name }}</h2>
                <p class="welcome-subtitle">Portal Dosen SIMAPRES</p>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4">
                <div class="col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-header">
                                <span class="stat-title">Mahasiswa Bimbingan</span>
                            </div>
                            <div class="stat-value" style="color: black;">{{ $totalBimbingan ?? 0 }}</div>
                            <div class="stat-subtitle"">Total mahasiswa yang dibimbing</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="stats-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-header">
                                <span class="stat-title">Menunggu Verifikasi</span>
                            </div>
                            <div class="stat-value" style="color: black;">{{ $totalPendingVerification ?? 0 }}</div>
                            <div class="stat-subtitle">Lomba yang perlu diverifikasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa Bimbingan -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        Daftar Mahasiswa Bimbingan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Program Studi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswaBimbingan ?? [] as $bimbingan)
                                    <tr class="@if ($bimbingan->status == 1) border-secondary @elseif($bimbingan->status == 2) border-success @elseif($bimbingan->status == 3) border-danger @endif">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    @if ($bimbingan->mahasiswa->detailUser->photo_file)
                                                        <img src="{{ asset('storage/' . $bimbingan->mahasiswa->detailUser->photo_file) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="User Avatar">
                                                    @else
                                                        <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $bimbingan->mahasiswa->detailUser->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $bimbingan->mahasiswa->detailUser->no_induk }}</td>
                                        <td>{{ $bimbingan->mahasiswa->detailUser->prodi->name ?? '-' }}</td>
                                        <td>{{ $bimbingan->tanggal_mulai ? \Carbon\Carbon::parse($bimbingan->tanggal_mulai)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>{{ $bimbingan->tanggal_selesai ? \Carbon\Carbon::parse($bimbingan->tanggal_selesai)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @if ($bimbingan->status == 1)
                                                <span class="badge bg-secondary">Belum Mulai</span>
                                            @elseif($bimbingan->status == 2)
                                                <span class="badge bg-success">Berjalan</span>
                                            @elseif($bimbingan->status == 3)
                                                <span class="badge bg-danger">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('bimbingan.index') }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-user-graduate fa-3x mb-3"></i>
                                                <p>Belum ada mahasiswa bimbingan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inputan Lomba -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Inputan Lomba
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul Lomba</th>
                                    <th>Penyelenggara</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($antrianVerifikasi ?? [] as $lomba)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <i class="fas fa-trophy fa-2x text-warning"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $lomba->judul }}</div>
                                                    <small>{{ $lomba->kategori }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $lomba->penyelenggara }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lomba->awal_registrasi)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($lomba->akhir_registrasi)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('lomba.show', $lomba->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('lomba.verify', $lomba->id) }}" class="btn btn-sm btn-success" title="Verifikasi">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty-state">
                                                <p>Tidak ada lomba yang diinput</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                .stats-card {
                    background: white;
                    border-radius: 15px;
                    padding: 1.5rem;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                    transition: transform 0.3s ease;
                }

                .stats-card:hover {
                    transform: translateY(-5px);
                }

                .stat-icon {
                    font-size: 2rem;
                    margin-bottom: 1rem;
                    color: #6B73FF;
                }

                .stat-value {
                    font-size: 2rem;
                    font-weight: bold;
                    margin: 0.5rem 0;
                    color: #000DFF;
                }

                .empty-state {
                    padding: 2rem;
                    text-align: center;
                    color: #6c757d;
                }

                .avatar-sm {
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .btn-group .btn {
                    padding: 0.375rem 0.75rem;
                    border: 1px solid #ddd;
                }

                .btn-group .btn:hover {
                    background: #f8f9fa;
                }
            </style>
        </div>
    @else
        <div class="alert alert-danger">
            Anda tidak memiliki akses ke halaman ini. Silakan hubungi administrator.
        </div>
    @endif

    <style>
        .dashboard-container {
            padding: 20px;
        }

        .welcome-section {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            padding: 30px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .welcome-subtitle {
            font-size: 16px;
            opacity: 0.8;
        }
    </style>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Data untuk chart bulanan
                const dataBulanan = @json($statistikPrestasi['bulanan']);
                const labelsBulanan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ];

                // Data untuk chart tahunan
                const dataTahunan = @json($statistikPrestasi['tahunan']);
                const labelsTahunan = dataTahunan.map(item => item.tahun);
                const valuesTahunan = dataTahunan.map(item => item.total);

                console.log('Data Bulanan:', dataBulanan);
                console.log('Data Tahunan:', dataTahunan);

                // Dapatkan nilai maksimum dari data bulanan
                const maxMonthlyValue = Math.max(...labelsBulanan.map((_, index) => {
                    const bulanData = dataBulanan.find(item => item.bulan === index + 1);
                    return bulanData ? bulanData.total : 0;
                }));

                // Dapatkan nilai maksimum dari data tahunan
                const maxYearlyValue = Math.max(...valuesTahunan);

                // Chart Bulanan
                const ctxBulanan = document.getElementById('prestasi-chart-canvas').getContext('2d');
                new Chart(ctxBulanan, {
                    type: 'bar',
                    data: {
                        labels: labelsBulanan,
                        datasets: [{
                            label: 'Jumlah Prestasi',
                            data: labelsBulanan.map((_, index) => {
                                const bulanData = dataBulanan.find(item => item.bulan ===
                                    index + 1);
                                return bulanData ? bulanData.total : 0;
                            }),
                            backgroundColor: 'rgba(54, 162, 235, 1)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: Math.max(20, Math.ceil(maxMonthlyValue / 10) * 10),
                                ticks: {
                                    stepSize: 10,
                                    precision: 0
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Statistik Prestasi Bulanan Tahun {{ $tahunSekarang }}',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 10,
                                    bottom: 20
                                }
                            },
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Jumlah Prestasi: ${context.raw}`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Chart Tahunan
                const ctxTahunan = document.getElementById('prestasi-trend-canvas').getContext('2d');
                new Chart(ctxTahunan, {
                    type: 'bar',
                    data: {
                        labels: labelsTahunan,
                        datasets: [{
                            label: 'Jumlah Prestasi',
                            data: valuesTahunan,
                            backgroundColor: 'rgba(75, 192, 192, 1)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            barPercentage: 0.8,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: Math.max(20, Math.ceil(maxYearlyValue / 10) * 10),
                                ticks: {
                                    stepSize: 10,
                                    precision: 0
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Tren Prestasi Tahunan (2020-2025)',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 10,
                                    bottom: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Jumlah Prestasi: ${context.raw}`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
