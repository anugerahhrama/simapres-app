<div>
    <div class="container-fluid mt-4">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Filter</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" wire:model.live="tingkatan_filter">
                                <option value="">Pilih Tingkatan</option>
                                @foreach ($tingkatanLomba as $tingkatan)
                                    <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-secondary" wire:click="resetFilter">
                                <i class="fa fa-refresh"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari lomba..." wire:model.live="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" wire:click="$refresh">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Card Layout Container -->
        <div class="card card-solid">
            <div class="card-body pb-0">
                <div class="row">
                    @forelse($lombas as $lomba)
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                            <div class="card bg-light d-flex flex-fill">
                                <div class="card-header text-muted border-bottom-0">
                                    {{ ucfirst($lomba->kategori) }}
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col">
                                            <h2 class="lead"><b>{{ $lomba->judul }}</b></h2>
                                            <p class="text-muted text-sm"><b>Tingkat: </b> {{ $lomba->tingkatanLomba->nama ?? '-' }} </p>
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Penyelenggara: {{ $lomba->penyelenggara }}</li>
                                                <li class="small">
                                                    <span class="fa-li"><i class="fas fa-lg fa-calendar"></i></span>
                                                    Tanggal: {{ \Carbon\Carbon::parse($lomba->awal_registrasi)->format('d M Y') }} - {{ \Carbon\Carbon::parse($lomba->akhir_registrasi)->format('d M Y') }}
                                                </li>
                                                <li class="small">
                                                    <span class="fa-li"><i class="fas fa-star"></i></span>
                                                    Skor Rekomendasi: {{ $lomba->skor }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ route('pendaftaran', base64_encode($lomba->id)) }}" class="btn btn-sm btn-primary">
                                            Daftar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Tidak ada data lomba yang ditemukan.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $lombas->links() }}
            </div>
        </div>
    </div>
</div>
