<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Poliklinik</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel d-flex mb-3 mt-3 pb-3">
            <div class="image">
                {{-- Use Gravatar when available; guard against guest users --}}
                @auth
                    @php
                        $email = trim(strtolower(Auth::user()->email ?? ''));
                        $hash = $email ? md5($email) : '';
                        $gravatar = $hash
                            ? "https://www.gravatar.com/avatar/{$hash}?s=100&d=mp"
                            : 'https://www.gravatar.com/avatar/?s=100&d=mp';
                    @endphp
                    <img src="{{ $gravatar }}" class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="https://www.gravatar.com/avatar/?s=100&d=mp" class="img-circle elevation-2" alt="User Image">
                @endauth
            </div>
            <div class="info">
                @auth
                    <a href="#" class="d-block">Halo! {{ Auth::user()->nama }}</a>
                @else
                    <a href="{{ route('login') }}" class="d-block">Halo! Guest</a>
                @endauth
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column">
                <!-- ROLE ADMIN -->
                @if (request()->is('admin*'))
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard Admin
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dokter.index') }}"
                            class="nav-link {{ request()->routeIs('admin.dokter.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Manajemen Dokter</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.poli.index') }}"
                            class="nav-link {{ request()->routeIs('admin.poli.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hospital"></i>
                            <p>
                                Manajemen Poli
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pasien.index') }}"
                            class="nav-link {{ request()->routeIs('admin.pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-injured"></i>
                            <p>
                                Manajemen Pasien
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.obat.index') }}"
                            class="nav-link {{ request()->routeIs('admin.obat.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-pills"></i>
                            <p>
                                Manajemen Obat
                            </p>
                        </a>
                    </li>
                @endif

                <!-- ROLE PASIEN -->
                @if (request()->is('pasien*'))
                    <li class="nav-item">
                        <a href="{{ route('pasien.dashboard') }}"
                            class="nav-link {{ request()->routeIs('pasien.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                Dashboard Pasien
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pasien.daftar-poli') }}"
                            class="nav-link {{ request()->routeIs('pasien.daftar-poli') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hospital-user"></i>
                            <p>
                                Daftar Poli
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pasien.riwayat') }}"
                            class="nav-link {{ request()->routeIs('pasien.riwayat') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Riwayat Pendaftaran
                            </p>
                        </a>
                    </li>
                @endif
                <!-- ROLE DOKTER -->
                @if (request()->is('dokter*'))
                    <li class="nav-item">
                        <a href="{{ route('dokter.dashboard') }}"
                            class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                Dashboard Dokter
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dokter.jadwal-periksa.index') }}"
                            class="nav-link {{ request()->routeIs('dokter.jadwal-periksa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>
                                Jadwal Periksa
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dokter.periksa-pasien.index') }}"
                            class="nav-link {{ request()->routeIs('dokter.periksa-pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-stethoscope"></i>
                            <p>
                                Periksa Pasien
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dokter.riwayat-pasien.index') }}"
                            class="nav-link {{ request()->routeIs('dokter.riwayat-pasien.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Pasien</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="nav-link btn btn-danger w-100 text-left">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Keluar</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
