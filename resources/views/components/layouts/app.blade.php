<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('components.partials.sidebar')
        <div class="content-wrapper">
            @include('components.partials.header')

            <!-- Flash messages handled by SweetAlert2 -->

            {{ $slot }}
        </div>
        @include('components.partials.footer')
    </div>

    <!-- jQuery (required for AdminLTE) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Bootstrap 4 JS (required for alerts and components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // SweetAlert Flash Messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('message'))
                Swal.fire({
                    icon: '{{ session('type', 'success') === 'success' ? 'success' : (session('type') === 'error' ? 'error' : 'info') }}',
                    title: '{{ session('type', 'success') === 'success' ? 'Berhasil!' : (session('type') === 'error' ? 'Error!' : 'Informasi') }}',
                    text: '{{ session('message') }}',
                    showConfirmButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    confirmButtonColor: '#28a745'
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    confirmButtonColor: '#28a745'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#dc3545'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: '{{ session('warning') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#ffc107'
                });
            @endif

            // Auto-dismiss regular alerts (fallback)
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-dismissible, .alert');
                alerts.forEach(function(alert) {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                });
            }, 100);
        });

        // Global SweetAlert Functions
        window.showSuccess = function(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                confirmButtonColor: '#28a745'
            });
        };

        window.showError = function(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#dc3545'
            });
        };

        window.confirmDelete = function(url, itemName = 'data ini') {
            return Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus ${itemName}. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    // CSRF Token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                        'content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    } else {
                        console.error('CSRF token not found');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'CSRF token tidak ditemukan. Silakan refresh halaman.',
                            confirmButtonColor: '#dc3545'
                        });
                        return false;
                    }

                    // Method DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
                return result.isConfirmed;
            });
        };

        window.confirmAction = function(title, text, confirmText = 'Ya', cancelText = 'Batal') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true
            });
        };

        // Confirm form submission with validation
        window.confirmSubmit = function(formId, title, text) {
            // Handle formId with or without # prefix
            const cleanFormId = formId.startsWith('#') ? formId.substring(1) : formId;
            const form = document.getElementById(cleanFormId);

            if (!form) {
                console.error('Form not found:', formId);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Form tidak ditemukan. Silakan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Basic client-side validation
            let isValid = true;
            let emptyFields = [];

            // Check required fields
            const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');

                    // Get field label
                    const label = form.querySelector(`label[for="${field.id}"]`) ||
                        form.querySelector(`label[for="${field.name}"]`);
                    const fieldName = label ? label.textContent.replace('*', '').trim() : field.name;
                    emptyFields.push(fieldName);
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap!',
                    html: `Harap lengkapi field berikut:<br><strong>${emptyFields.join(', ')}</strong>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ffc107'
                });
                return;
            }

            // If validation passed, show confirmation
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        };
    </script>

    @stack('scripts')
</body>

</html>
