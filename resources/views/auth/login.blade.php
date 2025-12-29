<x-layouts.auth title="Login">
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

            /* Animated Background Shapes */
            body::before {
                content: '';
                position: fixed;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent);
                top: -200px;
                left: -200px;
                animation: float 15s ease-in-out infinite;
                z-index: 1;
            }

            body::after {
                content: '';
                position: fixed;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent);
                bottom: -150px;
                right: -150px;
                animation: float 20s ease-in-out infinite reverse;
                z-index: 1;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translate(0, 0);
                }

                50% {
                    transform: translate(50px, 50px);
                }
            }

            /* Main Container */
            .login-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 1100px;
                margin: 2rem auto;
                min-height: calc(100vh - 4rem);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .login-box {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
                overflow: visible;
                animation: slideUp 0.6s ease-out;
                display: grid;
                grid-template-columns: 1fr 1fr;
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
            .login-info {
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                padding: 3rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
            }

            .info-icon {
                width: 120px;
                height: 120px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 2rem;
                font-size: 4rem;
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-15px);
                }
            }

            .info-title {
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 1rem;
                line-height: 1.3;
            }

            .info-subtitle {
                font-size: 1.1rem;
                opacity: 0.95;
                margin-bottom: 2.5rem;
                line-height: 1.6;
            }

            .info-features {
                width: 100%;
                text-align: left;
            }

            .feature-item {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin-bottom: 0.8rem;
                transition: all 0.3s ease;
            }

            .feature-item:hover {
                background: rgba(255, 255, 255, 0.25);
                transform: translateX(5px);
            }

            .feature-icon-small {
                width: 45px;
                height: 45px;
                background: rgba(255, 255, 255, 0.25);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.3rem;
                flex-shrink: 0;
            }

            .feature-text {
                font-size: 0.95rem;
                line-height: 1.4;
            }

            /* Right Side - Form */
            .login-form-side {
                display: flex;
                flex-direction: column;
            }

            /* Header */
            .login-header {
                padding: 2.5rem 2.5rem 2rem;
                text-align: center;
            }

            .login-icon {
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

            .login-header h1 {
                font-size: 1.7rem;
                font-weight: 800;
                color: #2d3748;
                margin-bottom: 0.4rem;
            }

            .login-header p {
                color: #718096;
                font-size: 0.9rem;
            }

            /* Body */
            .login-body {
                padding: 0 2.5rem 2.5rem;
            }

            .form-group {
                margin-bottom: 1.3rem;
            }

            .form-label {
                display: block;
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .input-group {
                position: relative;
            }

            .input-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #a0aec0;
                font-size: 1rem;
                z-index: 2;
            }

            .form-control {
                width: 100%;
                padding: 0.95rem 1rem 0.95rem 2.8rem;
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

            .form-control:focus~.input-icon {
                color: #11998e;
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
                margin: 1.5rem 0;
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

            /* Register Section */
            .register-section {
                text-align: center;
                padding: 1.3rem;
                background: #f7fafc;
                border-radius: 12px;
            }

            .register-section p {
                margin: 0 0 0.8rem;
                color: #4a5568;
                font-size: 0.9rem;
            }

            .btn-register {
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

            .btn-register:hover {
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
                .login-box {
                    grid-template-columns: 1fr;
                }

                .login-info {
                    display: none;
                }

                .login-container {
                    min-height: auto;
                }
            }

            @media (max-width: 576px) {
                body {
                    padding: 1rem;
                }

                .login-container {
                    margin: 1rem auto;
                    min-height: auto;
                }

                .login-header {
                    padding: 2rem 1.8rem 1.8rem;
                }

                .login-header h1 {
                    font-size: 1.5rem;
                }

                .login-body {
                    padding: 0 1.8rem 1.8rem;
                }

                .login-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 1.8rem;
                }
            }
        </style>
    @endpush

    <div class="login-container">
        <div class="login-box">
            <!-- Left Side - Info Panel -->
            <div class="login-info">
                <div class="info-icon">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <h2 class="info-title">Selamat Datang di<br>Sistem Poliklinik</h2>
                <p class="info-subtitle">Platform manajemen kesehatan digital yang memudahkan akses layanan medis Anda
                </p>

                <div class="info-features">
                    <div class="feature-item">
                        <div class="feature-icon-small">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Dokter Profesional</strong><br>
                            Konsultasi dengan tenaga medis berpengalaman
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon-small">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Jadwal Fleksibel</strong><br>
                            Booking appointment sesuai kebutuhan Anda
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon-small">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Data Aman</strong><br>
                            Privasi dan keamanan data terjamin
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-side">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h1>Welcome Back!</h1>
                    <p>Login ke Sistem Poliklinik</p>
                </div>

                <div class="login-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda"
                                    required autofocus>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" placeholder="Masukkan password Anda" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-sign-in-alt"></i> Login Sekarang
                        </button>
                    </form>

                    <div class="divider">
                        <span>ATAU</span>
                    </div>

                    <div class="register-section">
                        <p>Belum punya akun?</p>
                        <a href="{{ route('register') }}" class="btn-register">
                            <i class="fas fa-user-plus"></i>
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
</x-layouts.auth>
