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
      /* Serenity x Rose Quartz */
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
  <div id="appOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 lg:hidden"></div>

  @include('components.partials.sidebar')

  <div class="min-h-screen lg:pl-72">
    @include('components.partials.header')

    <main class="px-4 py-6 lg:px-8">
      {{ $slot }}
    </main>

    @include('components.partials.footer')
  </div>

  <div id="toastRoot" class="fixed right-4 top-4 z-[100] space-y-2"></div>

  <div id="confirmBackdrop" class="fixed inset-0 z-[110] hidden bg-slate-900/40"></div>
  <div id="confirmModal" class="fixed left-1/2 top-1/2 z-[111] hidden w-[92vw] max-w-md -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
    <div class="p-5">
      <div class="flex items-start gap-3">
        <div id="confirmIcon" class="grid h-11 w-11 place-items-center rounded-2xl bg-slate-100 text-slate-700">
          <i class="fa-solid fa-circle-question"></i>
        </div>
        <div class="min-w-0">
          <div id="confirmTitle" class="text-base font-bold text-slate-900">Konfirmasi</div>
          <div id="confirmMessage" class="mt-1 text-sm leading-relaxed text-slate-600"></div>
        </div>
      </div>
    </div>
    <div class="flex flex-col-reverse gap-2 border-t border-slate-200 p-4 sm:flex-row sm:justify-end">
      <button id="confirmCancel" type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
      <button id="confirmOk" type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold btn-brand">Ya, lanjut</button>
    </div>
  </div>

  @stack('scripts')

  <script>
    (function () {
      const overlay = document.getElementById('appOverlay');
      const sidebar = document.getElementById('appSidebar');

      function openSidebar(){
        if (!sidebar || !overlay) return;
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
      }
      function closeSidebar(){
        if (!sidebar || !overlay) return;
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
      }

      document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-sidebar-toggle]');
        if (btn) { e.preventDefault(); openSidebar(); }
        const closeBtn = e.target.closest('[data-sidebar-close]');
        if (closeBtn) { e.preventDefault(); closeSidebar(); }
        if (e.target === overlay) closeSidebar();
      });

      // Dropdown (user menu)
      document.addEventListener('click', (e) => {
        const toggler = e.target.closest('[data-dropdown-toggle]');
        const anyMenu = document.querySelector('[data-dropdown-menu]');
        if (toggler) {
          e.preventDefault();
          const menu = document.querySelector(toggler.getAttribute('data-dropdown-toggle'));
          if (!menu) return;
          const isHidden = menu.classList.contains('hidden');
          document.querySelectorAll('[data-dropdown-menu]').forEach(m => m.classList.add('hidden'));
          if (isHidden) menu.classList.remove('hidden');
          return;
        }
        if (anyMenu && !e.target.closest('[data-dropdown-menu]')) {
          document.querySelectorAll('[data-dropdown-menu]').forEach(m => m.classList.add('hidden'));
        }
      });

      // Toasts
      const toastRoot = document.getElementById('toastRoot');
      const icons = { success:'fa-circle-check', error:'fa-circle-xmark', warning:'fa-triangle-exclamation', info:'fa-circle-info' };
      const tone = {
        success:'border-emerald-200 bg-emerald-50 text-emerald-800',
        error:'border-rose-200 bg-rose-50 text-rose-800',
        warning:'border-amber-200 bg-amber-50 text-amber-900',
        info:'border-slate-200 bg-white text-slate-800',
      };

      window.toast = function ({ type='success', message='', title='', duration=2600 } = {}) {
        if (!toastRoot || !message) return;

        const wrap = document.createElement('div');
        wrap.className = `w-[92vw] max-w-sm overflow-hidden rounded-2xl border shadow-sm ${tone[type] || tone.info}`;
        wrap.style.transform = 'translateY(-6px)';
        wrap.style.opacity = '0';
        wrap.style.transition = 'all .18s ease';

        wrap.innerHTML = `
          <div class="flex items-start gap-3 p-4">
            <div class="mt-0.5 grid h-9 w-9 place-items-center rounded-xl bg-white/70">
              <i class="fa-solid ${icons[type] || icons.info}"></i>
            </div>
            <div class="min-w-0 flex-1">
              ${title ? `<div class="text-sm font-bold">${title}</div>` : ''}
              <div class="text-sm leading-relaxed">${message}</div>
            </div>
            <button class="ml-2 rounded-xl px-2 py-1 text-slate-600 hover:bg-white/60" aria-label="Close">
              <i class="fa-solid fa-xmark text-xs"></i>
            </button>
          </div>
        `;

        toastRoot.appendChild(wrap);
        requestAnimationFrame(() => {
          wrap.style.transform = 'translateY(0)';
          wrap.style.opacity = '1';
        });

        const close = () => {
          wrap.style.transform = 'translateY(-6px)';
          wrap.style.opacity = '0';
          setTimeout(() => wrap.remove(), 180);
        };

        wrap.querySelector('button')?.addEventListener('click', close);
        setTimeout(close, duration);
      };

      // Flash from server
      const flash = {
        message: @json(session('message')),
        type: @json(session('type')),
        success: @json(session('success')),
        error: @json(session('error')),
        warning: @json(session('warning')),
        info: @json(session('info')),
      };

      document.addEventListener('DOMContentLoaded', () => {
        if (flash.message) toast({ type: flash.type || 'success', message: flash.message });
        if (flash.success) toast({ type: 'success', message: flash.success });
        if (flash.error) toast({ type: 'error', message: flash.error });
        if (flash.warning) toast({ type: 'warning', message: flash.warning });
        if (flash.info) toast({ type: 'info', message: flash.info });
      });

      // Confirm modal
      const b = document.getElementById('confirmBackdrop');
      const m = document.getElementById('confirmModal');
      const t = document.getElementById('confirmTitle');
      const msg = document.getElementById('confirmMessage');
      const ok = document.getElementById('confirmOk');
      const cancel = document.getElementById('confirmCancel');
      const icon = document.getElementById('confirmIcon');

      function closeConfirm() {
        if (!b || !m) return;
        b.classList.add('hidden');
        m.classList.add('hidden');
      }

      window.confirmModal = function ({ title='Konfirmasi', message='Lanjutkan aksi ini?', confirmText='Ya, lanjut', cancelText='Batal', variant='brand' } = {}) {
        return new Promise((resolve) => {
          if (!b || !m || !t || !msg || !ok || !cancel) return resolve(false);

          t.textContent = title;
          msg.textContent = message;
          ok.textContent = confirmText;
          cancel.textContent = cancelText;

          ok.className = 'rounded-2xl px-4 py-2 text-sm font-semibold ' + (variant === 'danger'
            ? 'bg-rose-600 text-white hover:bg-rose-700'
            : 'btn-brand');

          if (icon) {
            icon.className = 'grid h-11 w-11 place-items-center rounded-2xl ' + (variant === 'danger'
              ? 'bg-rose-50 text-rose-700'
              : 'bg-slate-100 text-slate-700');
            icon.innerHTML = variant === 'danger'
              ? '<i class="fa-solid fa-triangle-exclamation"></i>'
              : '<i class="fa-solid fa-circle-question"></i>';
          }

          b.classList.remove('hidden');
          m.classList.remove('hidden');

          const done = (v) => {
            ok.removeEventListener('click', onOk);
            cancel.removeEventListener('click', onCancel);
            b.removeEventListener('click', onCancel);
            closeConfirm();
            resolve(v);
          };

          const onOk = () => done(true);
          const onCancel = () => done(false);

          ok.addEventListener('click', onOk);
          cancel.addEventListener('click', onCancel);
          b.addEventListener('click', onCancel);
        });
      };

      window.confirmDelete = async function (actionUrl, label='data') {
        const yes = await window.confirmModal({
          title: 'Hapus data?',
          message: `Yakin ingin menghapus ${label}? Aksi ini tidak bisa dibatalkan.`,
          confirmText: 'Hapus',
          cancelText: 'Batal',
          variant: 'danger'
        });
        if (!yes) return;

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = actionUrl;
        form.innerHTML = `<input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
      };


      // Confirm submit helper (used by admin forms)
      // Usage: confirmSubmit('#myForm', 'Simpan data?', 'Data akan disimpan ke database.')
      window.confirmSubmit = async function (formSelectorOrEl, title = "Simpan perubahan?", message = "Pastikan data sudah benar sebelum disimpan.") {
        const form = (typeof formSelectorOrEl === "string")
          ? document.querySelector(formSelectorOrEl)
          : formSelectorOrEl;

        if (!form) {
          window.toast?.({ type: "error", message: "Form tidak ditemukan." });
          return;
        }

        // If confirmModal exists, ask; otherwise submit directly
        if (typeof window.confirmModal === "function") {
          const yes = await window.confirmModal({
            title,
            message,
            confirmText: "Simpan",
            cancelText: "Batal",
            variant: "brand",
          });
          if (!yes) return;
        }

        form.submit();
      };

    })();
  </script>
</body>
</html>

