@php
    $user = Auth::user();
    $role = $user->role ?? null;
    $pageTitle = $title ?? (request()->is('admin*') ? 'Admin' : (request()->is('dokter*') ? 'Dokter' : 'Pasien'));

    $dashboardUrl = match ($role) {
        'admin' => route('admin.dashboard'),
        'dokter' => route('dokter.dashboard'),
        'pasien' => route('pasien.dashboard'),
        default => route('home'),
    };
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur">
    <div class="flex items-center justify-between gap-3 px-4 py-3 lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button" class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 lg:hidden" data-sidebar-toggle>
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="hidden sm:block">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $role ? strtoupper($role) : 'APP' }}</div>
                <div class="text-base font-bold tracking-tight text-slate-900">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ $dashboardUrl }}" class="hidden items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:inline-flex">
                <i class="fa-solid fa-house text-xs text-slate-500"></i>
                Dashboard
            </a>

            <div class="relative">
                <button type="button" class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50" data-dropdown-toggle="#userMenu">
                    <span class="hidden max-w-[140px] truncate sm:inline">{{ $user->nama ?? 'User' }}</span>
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-100 text-slate-700">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                </button>

                <div id="userMenu" data-dropdown-menu class="absolute right-0 mt-2 hidden w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
                    <div class="border-b border-slate-200 px-4 py-3">
                        <div class="truncate text-sm font-bold text-slate-900">{{ $user->nama ?? 'User' }}</div>
                        <div class="truncate text-xs text-slate-500">{{ $user->email ?? '-' }}</div>
                    </div>

                    <div class="p-2">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mt-1 flex w-full items-center gap-3 rounded-xl px-3 py-2 text-sm text-rose-700 hover:bg-rose-50">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
