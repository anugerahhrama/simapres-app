@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Filter</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" id="tingkatan_filter" name="tingkatan_filter">
                                <option value="">Pilih Tingkatan</option>
                                @foreach ($tingkatanLomba as $tingkatan)
                                    <option value="{{ $tingkatan->id }}">{{ $tingkatan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-secondary" id="resetFilter">
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
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari lomba...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
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
                {{-- <div class="row" id="lombaCardsContainer"> --}}
                <div class="row">
                    
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-muted border-bottom-0">
                                Teknologi
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col">
                                        <h2 class="lead"><b>Inovasi Teknologi Masa Depan</b></h2>
                                        <p class="text-muted text-sm"><b>Tingkat: </b> Universitas </p>
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Penyelenggara: Universitas Gadjah Mada</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-calendar"></i></span> Tanggal: 01 Januari 2025 - 15 Januari 2025</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="#" class="btn btn-sm btn-primary">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div class="row" id="loadingSpinner" style="display: none;">
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memuat data lomba...</p>
            </div>
        </div>

        <!-- No Data Message -->
        <div class="row" id="noDataMessage" style="display: none;">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Tidak ada data lomba yang ditemukan.
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center" id="paginationContainer">
                        <!-- Pagination will be loaded here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
    <style>
        .lomba-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            height: 100%;
        }

        .lomba-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .card-category {
            background-color: #e9ecef;
            color: #6c757d;
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .card-title-custom {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.3;
            min-height: 2.6rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .level-badge {
            background-color: #f8f9fa;
            color: #495057;
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .card-meta {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .card-meta i {
            width: 16px;
            margin-right: 8px;
            color: #6c757d;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            margin-bottom: 10px;
            display: inline-block;
        }

        .btn-daftar {
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s ease;
            color: white;
        }

        .btn-daftar:hover {
            background-color: #0056b3;
            color: white;
        }

        .btn-detail {
            background-color: #6c757d;
            border: none;
            border-radius: 6px;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.2s ease;
            color: white;
            margin-top: 5px;
        }

        .btn-detail:hover {
            background-color: #545b62;
            color: white;
        }

        .card-actions {
            margin-top: auto;
            padding-top: 10px;
        }

        .lomba-card .card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        @media (max-width: 768px) {
            .lomba-card {
                margin-bottom: 15px;
            }

            .card-title-custom {
                font-size: 1rem;
                min-height: 2.4rem;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var currentPage = 1;
        var itemsPerPage = 12;
        var totalPages = 1;
        var isLoading = false;

        $(document).ready(function() {
            loadLombaCards();

            // Filter event handlers
            $('#kategori_filter, #tingkatan_filter').on('change', function() {
                currentPage = 1;
                loadLombaCards();
            });

            // Search functionality
            var searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadLombaCards();
                }, 500);
            });

            $('#searchBtn').click(function() {
                currentPage = 1;
                loadLombaCards();
            });

            // Reset filter
            $('#resetFilter').click(function() {
                $('#kategori_filter').val('');
                $('#tingkatan_filter').val('');
                $('#searchInput').val('');
                currentPage = 1;
                loadLombaCards();
            });
        });

        function loadLombaCards() {
            if (isLoading) return;

            isLoading = true;
            $('#loadingSpinner').show();
            $('#noDataMessage').hide();

            var requestData = {
                _token: "{{ csrf_token() }}",
                page: currentPage,
                per_page: itemsPerPage
            };

            // Add filters
            var kategoriFilter = $('#kategori_filter').val();
            var tingkatanFilter = $('#tingkatan_filter').val();
            var searchQuery = $('#searchInput').val();

            if (kategoriFilter) {
                requestData.kategori_filter = kategoriFilter;
            }

            if (tingkatanFilter) {
                requestData.tingkatan_filter = tingkatanFilter;
            }

            if (searchQuery) {
                requestData.search = searchQuery;
            }

            $.ajax({
                url: "{{ route('lomba.list') }}",
                type: 'GET',
                data: requestData,
                success: function(response) {
                    isLoading = false;
                    $('#loadingSpinner').hide();

                    if (response.data && response.data.length > 0) {
                        displayCards(response.data);
                        updatePaginationInfo(response.meta);
                        setupPagination(response.meta);
                    } else {
                        $('#lombaCardsContainer').empty();
                        $('#noDataMessage').show();
                        $('#paginationContainer').empty();
                    }
                },
                error: function() {
                    isLoading = false;
                    $('#loadingSpinner').hide();
                    $('#lombaCardsContainer').empty();
                    $('#noDataMessage').show();
                    $('#paginationContainer').empty();
                }
            });
        }

        function displayCards(lombas) {
            var container = $('#lombaCardsContainer');
            container.empty();

            $('#noDataMessage').hide();

            lombas.forEach(function(lomba) {
                var card = createLombaCard(lomba);
                container.append(card);
            });
        }

        function createLombaCard(lomba) {
            var kategoriClass = lomba.kategori === 'Akademik' ? 'primary' : 'info';
            var statusClass = getStatusClass(lomba.status_verifikasi);
            var statusText = getStatusText(lomba.status_verifikasi);

            var awalRegistrasi = lomba.awal_registrasi ? new Date(lomba.awal_registrasi).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : '-';
            var akhirRegistrasi = lomba.akhir_registrasi ? new Date(lomba.akhir_registrasi).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : '-';

            return `
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card lomba-card">
                <div class="card-body">
                    <div class="card-category">${lomba.kategori}</div>
                    <h5 class="card-title-custom">${lomba.judul}</h5>
                    <div class="level-badge">Tingkat: ${lomba.nama || 'Tidak ditentukan'}</div>
                    
                    <div class="card-meta">
                        <i class="fas fa-building"></i>
                        <span>Penyelenggara: ${lomba.penyelenggara}</span>
                    </div>
                    
                    <div class="card-meta">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Tanggal: ${awalRegistrasi} - ${akhirRegistrasi}</span>
                    </div>
                    
                    <div class="card-meta">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Keahlian: ${lomba.keahlian_nama || 'Tidak ditentukan'}</span>
                    </div>
                    
                    <div class="card-meta">
                        <i class="fas fa-heart"></i>
                        <span>Minat: ${lomba.minat_nama || 'Tidak ditentukan'}</span>
                    </div>
                    
                    <div class="status-badge badge-${statusClass}">${statusText}</div>
                    
                    <div class="card-actions">
                        <button onclick="modalAction('/lombas/${lomba.id}')" class="btn btn-daftar">
                            Daftar
                        </button>
                        <button onclick="modalAction('/lombas/${lomba.id}')" class="btn btn-detail">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
        }

        function getStatusClass(status) {
            switch (status) {
                case 'approved':
                    return 'success';
                case 'pending':
                    return 'warning';
                case 'rejected':
                    return 'danger';
                default:
                    return 'secondary';
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'approved':
                    return 'Disetujui';
                case 'pending':
                    return 'Menunggu';
                case 'rejected':
                    return 'Ditolak';
                default:
                    return 'Tidak diketahui';
            }
        }

        function updatePaginationInfo(meta) {
            totalPages = meta.last_page;
            currentPage = meta.current_page;
        }

        function setupPagination(meta) {
            var paginationContainer = $('#paginationContainer');
            paginationContainer.empty();

            if (meta.last_page <= 1) {
                return;
            }

            // Previous button
            var prevDisabled = meta.current_page === 1 ? 'disabled' : '';
            paginationContainer.append(`
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" onclick="changePage(${meta.current_page - 1}); return false;">Previous</a>
        </li>
    `);

            // Calculate page range to show
            var startPage = Math.max(1, meta.current_page - 2);
            var endPage = Math.min(meta.last_page, meta.current_page + 2);

            // First page
            if (startPage > 1) {
                paginationContainer.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(1); return false;">1</a>
            </li>
        `);
                if (startPage > 2) {
                    paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
            }

            // Page numbers
            for (var i = startPage; i <= endPage; i++) {
                var activeClass = i === meta.current_page ? 'active' : '';
                paginationContainer.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
            </li>
        `);
            }

            // Last page
            if (endPage < meta.last_page) {
                if (endPage < meta.last_page - 1) {
                    paginationContainer.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                paginationContainer.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="changePage(${meta.last_page}); return false;">${meta.last_page}</a>
            </li>
        `);
            }

            // Next button
            var nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
            paginationContainer.append(`
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" onclick="changePage(${meta.current_page + 1}); return false;">Next</a>
        </li>
    `);

            // Add pagination info
            paginationContainer.after(`
        <div class="text-center mt-2">
            <small class="text-muted">
                Menampilkan ${meta.from || 0} - ${meta.to || 0} dari ${meta.total} data
            </small>
        </div>
    `);
        }

        function changePage(page) {
            if (page < 1 || page > totalPages || page === currentPage || isLoading) {
                return;
            }

            currentPage = page;
            loadLombaCards();

            // Scroll to top
            $('html, body').animate({
                scrollTop: $('#lombaCardsContainer').offset().top - 100
            }, 500);
        }
    </script>
@endpush
