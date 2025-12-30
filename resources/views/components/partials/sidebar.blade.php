@php
    $user = Auth::user();
    $role = $user->role ?? null;

    $brandHref = match ($role) {
        'admin' => route('admin.dashboard'),
        'dokter' => route('dokter.dashboard'),
        'pasien' => route('pasien.dashboard'),
        default => route('home'),
    };

    $isActiveRoute = fn(string $pattern) => request()->routeIs($pattern)
        ? 'bg-slate-100 text-slate-900'
        : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900';

    // Gravatar
    $email = trim(strtolower($user->email ?? ''));
    $hash = $email ? md5($email) : '';
    $avatar = $hash
        ? "https://www.gravatar.com/avatar/{$hash}?s=120&d=mp"
        : 'https://www.gravatar.com/avatar/?s=120&d=mp';

    $roleLabel = method_exists($user, 'getRoleLabel') ? $user->getRoleLabel() : ucfirst($role ?? 'User');
@endphp

<aside id="appSidebar" class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-slate-200 bg-white transition-transform duration-200 lg:translate-x-0">
    <!-- Top brand -->
    <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
        <a href="{{ $brandHref }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-2xl btn-brand ring-brand shadow-sm">
                <i class="fa-solid fa-hospital"></i>
            </span>
            <div class="leading-tight">
                <div class="text-sm font-bold tracking-tight text-slate-900">Poliklinik</div>
                <div class="text-xs text-slate-500">Dashboard</div>
            </div>
        </a>

        <button type="button" class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 lg:hidden" data-sidebar-close>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- User card -->
    <div class="border-b border-slate-200 px-5 py-4">
        <div class="flex items-center gap-3">
            <img src="{{ $avatar }}" alt="User" class="h-11 w-11 rounded-2xl object-cover ring-1 ring-slate-200" />
            <div class="min-w-0">
                <div class="truncate text-sm font-bold text-slate-900">{{ $user->nama ?? 'User' }}</div>
                <div class="mt-1 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2 py-1 text-[11px] font-semibold badge-brand">{{ $roleLabel }}</span>
                    @if (($role ?? '') === 'dokter' && optional($user->poli)->nama_poli)
                        <span class="text-xs text-slate-500">{{ $user->poli->nama_poli }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Nav -->
    <div class="h-[calc(100vh-220px)] overflow-auto px-3 py-3">
        @if (request()->is('admin*'))
            <div class="px-2 pb-2 pt-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Menu Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold {{ $isActiveRoute('admin.dashboard') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-gauge-high text-slate-700"></i></span>
                Dashboard
            </a>

            <div class="px-2 pb-2 pt-4 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Data Master</div>
            <a href="{{ route('admin.poli.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('admin.poli.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-building text-slate-700"></i></span>
                Poli
            </a>
            <a href="{{ route('admin.dokter.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('admin.dokter.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-user-doctor text-slate-700"></i></span>
                Dokter
            </a>
            <a href="{{ route('admin.pasien.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('admin.pasien.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-users text-slate-700"></i></span>
                Pasien
            </a>
            <a href="{{ route('admin.obat.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('admin.obat.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-pills text-slate-700"></i></span>
                Obat
            </a>
        @endif

        @if (request()->is('dokter*'))
            <div class="px-2 pb-2 pt-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Menu Dokter</div>
            <a href="{{ route('dokter.dashboard') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold {{ $isActiveRoute('dokter.dashboard') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-gauge-high text-slate-700"></i></span>
                Dashboard
            </a>
            <a href="{{ route('dokter.jadwal-periksa.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('dokter.jadwal-periksa.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-regular fa-calendar-check text-slate-700"></i></span>
                Jadwal Periksa
            </a>
            <a href="{{ route('dokter.periksa-pasien.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('dokter.periksa-pasien.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-stethoscope text-slate-700"></i></span>
                Periksa Pasien
            </a>
            <a href="{{ route('dokter.riwayat-pasien.index') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('dokter.riwayat-pasien.*') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-clock-rotate-left text-slate-700"></i></span>
                Riwayat Pasien
            </a>
        @endif

        @if (request()->is('pasien*'))
            <div class="px-2 pb-2 pt-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Menu Pasien</div>
            <a href="{{ route('pasien.dashboard') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm font-semibold {{ $isActiveRoute('pasien.dashboard') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-gauge-high text-slate-700"></i></span>
                Dashboard
            </a>
            <a href="{{ route('pasien.daftar-poli') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('pasien.daftar-poli') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-hospital-user text-slate-700"></i></span>
                Daftar Poli
            </a>
            <a href="{{ route('pasien.riwayat') }}" class="mb-1 flex items-center gap-3 rounded-2xl px-3 py-2 text-sm {{ $isActiveRoute('pasien.riwayat') }}">
                <span class="grid h-9 w-9 place-items-center rounded-2xl bg-white ring-1 ring-slate-200"><i class="fa-solid fa-clipboard-list text-slate-700"></i></span>
                Riwayat Pendaftaran
            </a>
        @endif

        
    </div>

    <!-- Footer actions -->
    <div class="border-t border-slate-200 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-2xl px-4 py-3 text-sm font-semibold btn-brand ring-brand">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
            </button>
        </form>
    </div>
</aside>
