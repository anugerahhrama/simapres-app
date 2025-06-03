<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-white elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="Simapres Logo" class="brand-image w-auto h-4" style="opacity: .8">
        <span class="brand-text font-weight-bold" style="color: #1a2151;">SIMAPRES</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel pb-3 mb-3 d-flex justify-items-start align-items-center">
            <div class="image">
                <img class="img-circle elevation-2" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->detailUser->name) }}&background=667eea&color=fff" alt="user photo" style="width: 40px; height: 40px;">
            </div>
            <div class="info">
                <a href="{{ route('profile.index') }}" class="d-block" style="color: #2d3748; font-weight: 600; text-decoration: none;">
                    {{ auth()->user()->detailUser->name ?? 'not found' }}
                </a>
                <small style="color: #718096;">{{ auth()->user()->level->nama_level ?? 'No Role' }}</small>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline mb-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" style="border-radius: 6px; border: 1px solid #e2e8f0;">
                <div class="input-group-append">
                    <button class="btn btn-sidebar" style="border-radius: 0 6px 6px 0;">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (auth()->check() && auth()->user()->level->level_code === 'ADM')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('*dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('*manajemen*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('*manajemen*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Manajemen
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('levels.index') }}" class="nav-link {{ request()->is('*level*') ? 'active' : '' }}" style="padding-left: 35px;">
                                    <i class="far fa-solid fa-user-gear nav-icon"></i>
                                    <p>Level</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('detailusers.index') }}" class="nav-link {{ request()->is('*detailusers*') ? 'active' : '' }}" style="padding-left: 35px;">
                                    <i class="fas fa-users nav-icon"></i>
                                    <p>Pengguna</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('periodes.index') }}" class="nav-link {{ request()->is('*periode*') ? 'active' : '' }}" style="padding-left: 35px;">
                                    <i class="far fa-solid fa-clock nav-icon"></i>
                                    <p>Periode</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('prodis.index') }}" class="nav-link {{ request()->is('*prodi*') ? 'active' : '' }}" style="padding-left: 35px;">
                                    <i class="fas fa-graduation-cap nav-icon"></i>
                                    <p>Program Studi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                        <a href="{{ route('lomba.index') }}"
                            class="nav-link {{ request()->is('*lomba*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-medal"></i>
                            <p>Lomba</p>
                        </a>
                    </li>
                        </ul>
                    </li>
                @endif

                @if (auth()->check() && auth()->user()->level->level_code === 'MHS')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('*dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('prestasi.index') }}" class="nav-link {{ request()->is('*prestasi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trophy"></i>
                            <p>Manajemen Prestasi</p>
                        </a>
                    </li>
                    <li class="nav-item bg- bg-[#1A2151]">
                        <a href="{{ route('lombas.index') }}" class="nav-link {{ request()->is('*lomba*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Lomba</p>
                        </a>
                    </li>
                    <li class="nav-item bg- bg-[#1A2151]">
                        <a href="{{ route('monitoring.index') }}" class="nav-link {{ request()->is('*monitoring*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Monitoring</p>
                        </a>
                    </li>
                @endif
        </nav>

        <!-- Logout Section -->
        <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-block" style="border-radius: 8px; padding: 12px; font-weight: 600; transition: all 0.2s;">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span class="collapse-sidebar">Logout</span>
                </button>
            </form>
        </div>
    </div>
    <!-- /.sidebar -->
</aside>

<style>
    /* Additional Sidebar Styling */

    .main-sidebar.sidebar-light-white {
        background-color: #ffffff !important;
    }

    .user-panel {
        padding-left: 0.7rem !important;
    }

    .user-panel .info {
        padding-left: 0.5rem !important;
    }

    .sidebar .nav-sidebar .nav-item .nav-link {
        color: #718096 !important;
        font-weight: 500 !important;
        padding-left: 0.7rem !important;
    }

    .sidebar .nav-sidebar .nav-item .nav-link:hover {
        background-color: #f1f5f9 !important;
        color: #6898f2 !important;
    }

    .sidebar .nav-sidebar .nav-item .nav-link.active {
        background-color: #4473ed !important;
        color: #ffffff !important;
    }

    .sidebar .nav-treeview .nav-item .nav-link {
        color: #718096 !important;
        padding-left: 0.7rem !important;
    }

    .sidebar .nav-treeview .nav-item .nav-link.active {
        background-color: #f0f5fe !important;
        color: #4473ed !important;
    }

    .sidebar .nav-treeview .nav-item .nav-link:hover {
        background-color: transparent !important;
        color: #6898f2 !important;
        border-left: 3px solid #6898f2 !important;
    }

    .btn-outline-danger {
        padding-left: 0.7rem !important;
    }

    .sidebar-collapse .collapse-sidebar {
        display: none !important;
    }

    .sidebar-collapse .btn-outline-danger {
        padding: 8px !important;
        text-align: center !important;
    }

    .sidebar-collapse .btn-outline-danger i {
        margin-right: 0 !important;
    }

    /* Aturan untuk Manajemen */
    .sidebar .nav-sidebar .nav-item:has(.nav-treeview)>.nav-link {
        background-color: transparent !important;
        color: #4a5568 !important;
    }

    .sidebar .nav-sidebar .nav-item.menu-is-opening:has(.nav-treeview)>.nav-link,
    .sidebar .nav-sidebar .nav-item.menu-open:has(.nav-treeview)>.nav-link,
    .sidebar .nav-sidebar .nav-item:has(.nav-treeview)>.nav-link.active {
        background-color: #4473ed !important;
        color: #ffffff !important;
    }
</style>
