<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Brand --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Simahaswa</div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- Menu: Dashboard --}}
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Data Master</div>

    {{-- Menu: Program Studi --}}
    <li class="nav-item {{ request()->routeIs('prodi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('prodi.index') }}">
            <i class="fas fa-fw fa-university"></i>
            <span>Program Studi</span>
        </a>
    </li>

    {{-- Menu: Mahasiswa --}}
    <li class="nav-item {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mahasiswa.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Mahasiswa</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Akademik</div>

    {{-- Menu: Nilai --}}
    <li class="nav-item {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('nilai.index') }}">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Nilai Mahasiswa</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    {{-- Sidebar Toggler --}}
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>