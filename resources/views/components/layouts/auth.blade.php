<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Poliklinik Digital - {{ $title ?? 'Login' }}">
    <meta name="author" content="Poliklinik">

    <title>{{ $title ?? 'Poliklinik' }} - Poliklinik Digital</title>

    <!-- Font Awesome from CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template-admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('styles')

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .bg-login-image {
            background: url('{{ asset('template-admin/img/undraw_posting_photo.svg') }}');
            background-position: center;
            background-size: cover;
        }

        .bg-register-image {
            background: url('{{ asset('template-admin/img/undraw_thought_process_67my.svg') }}');
            background-position: center;
            background-size: cover;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: #5a5c69;
            text-decoration: none;
            font-weight: 700;
        }

        .brand-logo:hover {
            color: #3a3b45;
            text-decoration: none;
        }

        .card {
            border-radius: 1rem;
        }

        .form-control-user {
            border-radius: 10rem;
            padding: 1.5rem 1rem;
        }

        .btn-user {
            border-radius: 10rem;
            padding: 0.75rem 1rem;
        }

        .floating-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .medical-icon {
            color: #11998e;
            margin-right: 0.5rem;
        }

        .demo-credentials {
            background: linear-gradient(135deg, #f8f9fc 0%, #e2e5e9 100%);
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #e3e6f0;
        }

        .demo-item {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        {{ $slot }}
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template-admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template-admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template-admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template-admin/js/sb-admin-2.min.js') }}"></script>

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
                    timer: 4000,
                    timerProgressBar: true,
                    confirmButtonColor: '#667eea'
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    showConfirmButton: true,
                    timer: 4000,
                    timerProgressBar: true,
                    confirmButtonColor: '#667eea'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true,
                    confirmButtonColor: '#e74a3b'
                });
            @endif
        });
    </script>

    @stack('scripts')

</body>

</html>
