<x-layouts.auth title="Home">
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
                position: relative;
                overflow-x: hidden;
            }

            /* Animated Background */
            .animated-bg {
                position: fixed;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                z-index: 1;
                overflow: hidden;
            }

            .floating-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
            }

            .shape {
                position: absolute;
                opacity: 0.1;
                animation: float 15s infinite ease-in-out;
            }

            .shape:nth-child(1) {
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
                top: -100px;
                left: -100px;
                animation-delay: 0s;
            }

            .shape:nth-child(2) {
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.2), transparent);
                bottom: -50px;
                right: -50px;
                animation-delay: 2s;
            }

            .shape:nth-child(3) {
                width: 250px;
                height: 250px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent);
                top: 50%;
                right: 10%;
                animation-delay: 4s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                33% {
                    transform: translate(30px, -30px) scale(1.1);
                }

                66% {
                    transform: translate(-20px, 20px) scale(0.9);
                }
            }

            /* Main Container */
            .main-wrapper {
                position: relative;
                z-index: 10;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }

            .hero-container {
                max-width: 1400px;
                width: 100%;
            }

            /* Header Section */
            .hero-header {
                text-align: center;
                margin-bottom: 4rem;
                animation: fadeInDown 0.8s ease-out;
            }

            .brand-logo {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 90px;
                height: 90px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 25px;
                margin-bottom: 2rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                animation: pulse 2s ease-in-out infinite;
            }

            .brand-logo i {
                font-size: 2.8rem;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }
            }

            .hero-title {
                font-size: 3.5rem;
                font-weight: 800;
                color: white;
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
                line-height: 1.2;
            }

            .hero-subtitle {
                font-size: 1.2rem;
                color: rgba(255, 255, 255, 0.95);
                font-weight: 400;
                max-width: 580px;
                margin: 0 auto;
                line-height: 1.6;
            }

            /* Action Cards */
            .action-cards {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem;
                margin-bottom: 4rem;
                max-width: 900px;
                margin-left: auto;
                margin-right: auto;
            }

            .card {
                background: rgba(255, 255, 255, 0.98);
                border-radius: 20px;
                padding: 2.5rem 2rem;
                text-align: center;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                animation: fadeInUp 0.8s ease-out both;
                position: relative;
                overflow: hidden;
            }

            .card:nth-child(1) {
                animation-delay: 0.3s;
            }

            .card:nth-child(2) {
                animation-delay: 0.5s;
            }

            .card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: linear-gradient(90deg, #11998e, #38ef7d);
                transform: scaleX(0);
                transition: transform 0.4s ease;
            }

            .card:hover::before {
                transform: scaleX(1);
            }

            .card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            }

            .card-icon {
                width: 70px;
                height: 70px;
                margin: 0 auto 1.5rem;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.4s ease;
            }

            .card:hover .card-icon {
                transform: rotateY(360deg);
            }

            .card-icon i {
                font-size: 2rem;
                color: white;
            }

            .card-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #2d3748;
                margin-bottom: 0.8rem;
            }

            .card-description {
                color: #718096;
                font-size: 0.95rem;
                line-height: 1.6;
                margin-bottom: 1.8rem;
            }

            .card-button {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.9rem 2.2rem;
                background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
                color: white;
                border-radius: 50px;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
            }

            .card-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 35px rgba(17, 153, 142, 0.6);
                color: white;
                text-decoration: none;
            }

            .card-button i {
                font-size: 0.9rem;
                transition: transform 0.3s ease;
            }

            .card-button:hover i {
                transform: translateX(3px);
            }

            /* Features Grid */
            .features-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1.5rem;
                max-width: 1100px;
                margin: 0 auto;
                animation: fadeInUp 0.8s ease-out 0.7s both;
            }

            .feature-card {
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
                border-radius: 20px;
                padding: 2rem 1.5rem;
                text-align: center;
                color: white;
                transition: all 0.3s ease;
            }

            .feature-card:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-5px);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                margin: 0 auto 1rem;
                background: rgba(255, 255, 255, 0.15);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
            }

            .feature-title {
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .feature-description {
                font-size: 0.9rem;
                opacity: 0.9;
                line-height: 1.4;
            }

            /* Animations */
            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .features-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }

                .hero-subtitle {
                    font-size: 1.05rem;
                }

                .action-cards {
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                }

                .features-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1rem;
                }

                .feature-card {
                    padding: 1.5rem 1rem;
                }
            }

            @media (max-width: 576px) {
                .main-wrapper {
                    padding: 1.5rem;
                }

                .hero-title {
                    font-size: 2rem;
                }

                .hero-subtitle {
                    font-size: 0.95rem;
                }

                .brand-logo {
                    width: 75px;
                    height: 75px;
                }

                .brand-logo i {
                    font-size: 2.2rem;
                }

                .card {
                    padding: 2rem 1.8rem;
                }

                .card-title {
                    font-size: 1.3rem;
                }

                .card-button {
                    padding: 0.85rem 2rem;
                    font-size: 0.9rem;
                }

                .features-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* Dark mode support (optional) */
            @media (prefers-color-scheme: dark) {
                .card {
                    background: rgba(255, 255, 255, 0.95);
                }
            }
        </style>
    @endpush

    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-wrapper">
        <div class="hero-container">
            <!-- Hero Header -->
            <div class="hero-header">
                <div class="brand-logo">
                    <i class="fas fa-hospital-alt"></i>
                </div>
                <h1 class="hero-title">Sistem Poliklinik</h1>
                <p class="hero-subtitle">
                    Layanan kesehatan terpadu dengan sistem manajemen modern untuk pengalaman medis yang lebih baik
                </p>
            </div>

            <!-- Action Cards -->
            <div class="action-cards">
                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h3 class="card-title">Login</h3>
                    <p class="card-description">
                        Sudah memiliki akun? Masuk untuk mengakses layanan poliklinik
                    </p>
                    <a href="{{ route('login') }}" class="card-button">
                        Masuk Sekarang
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="card">
                    <div class="card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="card-title">Registrasi</h3>
                    <p class="card-description">
                        Pasien baru? Daftar sekarang dan dapatkan pelayanan terbaik
                    </p>
                    <a href="{{ route('register') }}" class="card-button">
                        Daftar Gratis
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4 class="feature-title">Dokter Berpengalaman</h4>
                    <p class="feature-description">Tenaga medis profesional dan terpercaya</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 class="feature-title">Jadwal Fleksibel</h4>
                    <p class="feature-description">Booking appointment kapan saja</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h4 class="feature-title">Obat Lengkap</h4>
                    <p class="feature-description">Stok obat terjamin dan berkualitas</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4 class="feature-title">Layanan Cepat</h4>
                    <p class="feature-description">Proses efisien dan sistematis</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.auth>
