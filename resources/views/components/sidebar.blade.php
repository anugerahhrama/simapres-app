<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-navy elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('assets/img/logo/logo1.png') }}" alt="AdminLTE Logo" class="brand-image w-auto h-4" style="opacity: .8">
        <span class="brand-text font-weight-bold" style="color: #1a2151;">SIMAPRES</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img class="img-circle elevation-2" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->email) }}" alt="user photo">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 d-flex flex-column">
            <ul class="nav nav-pills nav-sidebar flex-column flex-grow-1" data-widget="treeview" role="menu" data-accordion="false">
                @if (auth()->check() && auth()->user()->level->level_code === 'ADM')
                    <li class="nav-item bg- bg-[#1A2151]">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('*dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item bg- bg-[#1A2151] {{ request()->is('*manajemen*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('*manajemen*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-gear"></i>
                            <p>Manajemen <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('levels.index') }}" class="nav-link {{ request()->is('*level*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Level</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('periodes.index') }}" class="nav-link {{ request()->is('*periode*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Periode</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="bd-links-span-all my-3 border-top"></li>

                <li class="nav-item bg-[#1A2151]">
                    <form action="{{ route('logout') }}" method="POST" class="nav-link">
                        @csrf
                        <button type="submit" class="btn btn-transparent p-0">
                            <i class="nav-icon fas fa-right-from-bracket"></i>
                            <p class="mb-0">Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
