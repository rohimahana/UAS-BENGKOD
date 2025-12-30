<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Poliklinik' }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root{
            --brand-bg-1:#92A8D1;
            --brand-bg-2:#F7CAC9;
            --brand-cta-1:#3F5FA8;
            --brand-cta-2:#A44A5C;
            --brand-ring: rgba(63,95,168,.18);
        }
        body{ font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
        .text-brand{ color: var(--brand-cta-1); }
        .btn-brand{ background: linear-gradient(135deg, var(--brand-cta-1) 0%, var(--brand-cta-2) 100%); color:#fff; border:0; }
        .btn-brand:hover{ filter: brightness(.97); }
        .badge-brand{ background: rgba(146,168,209,.16); color: var(--brand-cta-1); border: 1px solid rgba(146,168,209,.28); }
        .ring-brand{ box-shadow: 0 0 0 3px var(--brand-ring); }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900">
    <div class="relative min-h-screen overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full blur-3xl" style="background: rgba(146,168,209,.35)"></div>
            <div class="absolute -right-32 top-24 h-96 w-96 rounded-full blur-3xl" style="background: rgba(247,202,201,.35)"></div>
            <div class="absolute bottom-0 left-1/2 h-96 w-[46rem] -translate-x-1/2 rounded-full bg-white blur-3xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl flex-col px-4 py-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl btn-brand ring-brand shadow-sm">
                        <i class="fa-solid fa-hospital"></i>
                    </span>
                    <div class="leading-tight">
                        <div class="text-sm font-bold tracking-tight text-slate-900">Poliklinik</div>
                        <div class="text-xs text-slate-500">Pendaftaran • Antrian • Pemeriksaan</div>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    <i class="fa-solid fa-arrow-left-long mr-2 text-xs text-slate-500"></i>
                    Kembali
                </a>
            </div>

            <div class="flex flex-1 items-center justify-center py-10">
                {{ $slot }}
            </div>

            <div class="text-center text-xs text-slate-500">© {{ date('Y') }} Poliklinik</div>
        </div>
    </div>

    <!-- Toast root -->
    <div id="toastRoot" class="fixed right-4 top-4 z-[100] space-y-2"></div>

    @stack('scripts')

    <script>
        (function(){
            const toastRoot = document.getElementById('toastRoot');
            const icons = { success:'fa-circle-check', error:'fa-circle-xmark', warning:'fa-triangle-exclamation', info:'fa-circle-info' };
            const tone = {
                success: 'border-emerald-200 bg-emerald-50 text-emerald-800',
                error: 'border-rose-200 bg-rose-50 text-rose-800',
                warning: 'border-amber-200 bg-amber-50 text-amber-900',
                info: 'border-slate-200 bg-white text-slate-800',
            };

            window.toast = function({ type='success', message='', title='', duration=2600 }={}){
                if(!toastRoot || !message) return;
                const wrap = document.createElement('div');
                wrap.className = `w-[92vw] max-w-sm overflow-hidden rounded-2xl border shadow-sm ${tone[type]||tone.info}`;
                wrap.style.transform = 'translateY(-6px)';
                wrap.style.opacity = '0';
                wrap.style.transition = 'all .18s ease';
                wrap.innerHTML = `
                    <div class="flex items-start gap-3 p-4">
                        <div class="mt-0.5 grid h-9 w-9 place-items-center rounded-xl bg-white/70"><i class="fa-solid ${icons[type]||icons.info}"></i></div>
                        <div class="min-w-0 flex-1">
                            ${title?`<div class="text-sm font-bold">${title}</div>`:''}
                            <div class="text-sm leading-relaxed">${message}</div>
                        </div>
                        <button class="ml-2 rounded-xl px-2 py-1 text-slate-600 hover:bg-white/60" aria-label="Close"><i class="fa-solid fa-xmark text-xs"></i></button>
                    </div>`;
                toastRoot.appendChild(wrap);
                requestAnimationFrame(()=>{ wrap.style.transform='translateY(0)'; wrap.style.opacity='1'; });
                const close=()=>{ wrap.style.transform='translateY(-6px)'; wrap.style.opacity='0'; setTimeout(()=>wrap.remove(),180); };
                wrap.querySelector('button')?.addEventListener('click', close);
                setTimeout(close, duration);
            };

            const flash = {
                message: @json(session('message')),
                type: @json(session('type')),
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info')),
            };
            document.addEventListener('DOMContentLoaded', ()=>{
                if (flash.message) toast({ type: flash.type || 'success', message: flash.message });
                if (flash.success) toast({ type: 'success', message: flash.success });
                if (flash.error) toast({ type: 'error', message: flash.error });
                if (flash.warning) toast({ type: 'warning', message: flash.warning });
                if (flash.info) toast({ type: 'info', message: flash.info });
            });
        })();
    </script>
</body>

</html>
