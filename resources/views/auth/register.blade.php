<x-layouts.auth title="Daftar Akun">
    @push('styles')
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                min-height: 100vh;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                padding: 2rem;
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
            }

            /* Animated Background */
            body::before {
                content: '';
                position: fixed;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
                top: -200px;
                right: -200px;
                animation: float 18s ease-in-out infinite;
                z-index: 1;
            }

            body::after {
                content: '';
                position: fixed;
                width: 350px;
                height: 350px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent);
                bottom: -180px;
                left: -180px;
                animation: float 22s ease-in-out infinite reverse;
                z-index: 1;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                50% {
                    transform: translate(-40px, 40px) scale(1.1);
                }
            }

            /* Main Container */
            .register-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 1200px;
                margin: 2rem auto;
                min-height: calc(100vh - 4rem);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .register-box {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
                overflow: visible;
                animation: slideUp 0.6s ease-out;
                display: grid;
                grid-template-columns: 1fr 1.3fr;
                width: 100%;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Left Side - Info Panel */
            .register-info {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                padding: 3rem 2.5rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
            }

            .info-icon-large {
                width: 110px;
                height: 110px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 2rem;
                font-size: 3.5rem;
                animation: floatRegister 3s ease-in-out infinite;
            }

            @keyframes floatRegister {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-12px) rotate(5deg);
                }
            }

            .info-title-register {
                font-size: 1.8rem;
                font-weight: 800;
                margin-bottom: 1rem;
                line-height: 1.3;
            }

            .info-subtitle-register {
                font-size: 1rem;
                opacity: 0.95;
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .info-steps {
                width: 100%;
                text-align: left;
            }

            .step-item {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin-bottom: 0.8rem;
                transition: all 0.3s ease;
            }

            .step-item:hover {
                background: rgba(255, 255, 255, 0.22);
                transform: translateX(5px);
            }

            .step-number {
                width: 35px;
                height: 35px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .step-text {
                font-size: 0.9rem;
                line-height: 1.5;
                padding-top: 0.3rem;
            }

            /* Right Side - Form */
            .register-form-side {
                display: flex;
                flex-direction: column;
            }

            /* Header */
            .register-header {
                padding: 2.2rem 2.5rem 1.8rem;
                text-align: center;
            }

            .register-icon {
                width: 70px;
                height: 70px;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.2rem;
                font-size: 2rem;
                color: white;
                box-shadow: 0 10px 30px rgba(17, 153, 142, 0.3);
            }

            .register-header h1 {
                font-size: 1.7rem;
                font-weight: 800;
                color: #2d3748;
                margin-bottom: 0.4rem;
            }

            .register-header p {
                color: #718096;
                font-size: 0.9rem;
            }

            /* Body */
            .register-body {
                padding: 0 2.5rem 2.5rem;
            }

            .form-row-custom {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .form-group {
                margin-bottom: 1.1rem;
            }

            .form-label {
                display: flex;
                align-items: center;
                gap: 0.4rem;
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .form-label i {
                color: #11998e;
                font-size: 0.85rem;
            }

            .form-control {
                width: 100%;
                padding: 0.9rem 1rem;
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                background: white;
                color: #2d3748;
            }

            .form-control:focus {
                outline: none;
                border-color: #11998e;
                box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.1);
            }

            textarea.form-control {
                resize: vertical;
                min-height: 70px;
            }

            .btn-submit {
                width: 100%;
                padding: 0.95rem;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                border: none;
                border-radius: 12px;
                color: white;
                font-size: 0.95rem;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 8px 20px rgba(17, 153, 142, 0.3);
                margin-top: 0.3rem;
            }

            .btn-submit:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 30px rgba(17, 153, 142, 0.4);
            }

            .btn-submit:active {
                transform: translateY(0);
            }

            /* Divider */
            .divider {
                display: flex;
                align-items: center;
                margin: 1.3rem 0;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #e2e8f0;
            }

            .divider span {
                padding: 0 1rem;
                color: #a0aec0;
                font-size: 0.85rem;
                font-weight: 600;
            }

            /* Login Section */
            .login-section {
                text-align: center;
                padding: 1.3rem;
                background: #f7fafc;
                border-radius: 12px;
            }

            .login-section p {
                margin: 0 0 0.8rem;
                color: #4a5568;
                font-size: 0.9rem;
            }

            .btn-login {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 2rem;
                background: white;
                border: 2px solid #11998e;
                color: #11998e;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .btn-login:hover {
                background: #11998e;
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(17, 153, 142, 0.25);
                text-decoration: none;
            }

            /* Alert */
            .alert {
                padding: 1rem;
                border-radius: 10px;
                margin-bottom: 1.5rem;
                font-size: 0.9rem;
            }

            .alert-danger {
                background: #fff5f5;
                border: 1px solid #feb2b2;
                color: #c53030;
            }

            .alert ul {
                margin: 0;
                padding-left: 1.2rem;
            }

            /* Responsive */
            @media (max-width: 992px) {
                .register-box {
                    grid-template-columns: 1fr;
                }

                .register-info {
                    display: none;
                }

                .register-container {
                    min-height: auto;
                }
            }

            @media (max-width: 768px) {
                .form-row-custom {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 576px) {
                body {
                    padding: 1rem;
                }

                .register-container {
                    margin: 1rem auto;
                    min-height: auto;
                }

                .register-header {
                    padding: 2rem 1.8rem 1.5rem;
                }

                .register-header h1 {
                    font-size: 1.5rem;
                }

                .register-body {
                    padding: 0 1.8rem 1.8rem;
                }

                .register-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 1.8rem;
                }
            }
        </style>
    @endpush

    <div class="register-container">
        <div class="register-box">
            <!-- Left Side - Info Panel -->
            <div class="register-info">
                <div class="info-icon-large">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="info-title-register">Bergabung dengan Kami</h2>
                <p class="info-subtitle-register">Daftar sekarang dan nikmati kemudahan akses layanan kesehatan</p>

                <div class="info-steps">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-text">
                            <strong>Isi Formulir Pendaftaran</strong><br>
                            Lengkapi data diri Anda dengan benar
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-text">
                            <strong>Verifikasi Akun</strong><br>
                            Sistem akan memproses data Anda
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-text">
                            <strong>Mulai Konsultasi</strong><br>
                            Booking jadwal dengan dokter pilihan
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-text">
                            <strong>Dapatkan Perawatan</strong><br>
                            Nikmati layanan kesehatan terbaik
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="register-form-side">
                <div class="register-header">
                    <div class="register-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1>Buat Akun Baru</h1>
                    <p>Daftar sebagai pasien poliklinik</p>
                </div>

                <div class="register-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required
                                autofocus>
                            @error('nama')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>Email
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                            @error('email')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Alamat
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat"
                                placeholder="Masukkan alamat lengkap" rows="2" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i>Nomor KTP
                                </label>
                                <input type="text" class="form-control @error('no_ktp') is-invalid @enderror"
                                    name="no_ktp" value="{{ old('no_ktp') }}" placeholder="16 digit" maxlength="16"
                                    required>
                                @error('no_ktp')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>Nomor HP
                                </label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                    name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
                                @error('no_hp')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Min. 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i>Konfirmasi Password
                                </label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="divider">
                        <span>ATAU</span>
                    </div>

                    <div class="login-section">
                        <p>Sudah punya akun?</p>
                        <a href="{{ route('login') }}" class="btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
</x-layouts.auth>
