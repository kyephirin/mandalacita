<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMB Mandala Cita | Penerimaan Mahasiswa Baru 2026</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            overflow-x: hidden;
        }

        /* ========== ANIMASI TIMBUL (POP-UP / SCALE-UP) ========== */
        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.7) translateY(30px);
            }
            60% {
                opacity: 0.9;
                transform: scale(1.02) translateY(-5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes borderGlow {
            0% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.3);
            }
            70% {
                box-shadow: 0 0 0 12px rgba(76, 175, 80, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(76, 175, 80, 0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        /* Navbar Sticky - alternatif untuk IE */
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 16px 40px;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* Spacer untuk fixed navbar */
        .navbar-spacer {
            height: 74px;
            display: block;
        }

        .navbar.scrolled {
            background-color: #1a252f;
            padding: 12px 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            transition: transform 0.3s;
        }

        .logo:hover {
            transform: scale(1.03);
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            padding: 6px 12px;
            border-radius: 30px;
            transition: all 0.3s;
            font-weight: 500;
            position: relative;
            display: inline-block;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #4caf50;
            transition: width 0.3s;
        }

        .nav-links a:hover::before {
            width: 70%;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* HERO dengan gambar.jpg - TEKS TANPA ANIMASI */
        .hero {
            background: linear-gradient(135deg, rgba(44, 62, 80, 0.85), rgba(0, 0, 0, 0.75)), url('gambar.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            padding: 130px 20px;
            position: relative;
        }

        .hero h1 {
            font-size: 3.8rem;
            margin-bottom: 18px;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
            font-weight: 700;
        }

        .hero .subtitle {
            font-size: 1.5rem;
            margin-bottom: 25px;
            font-weight: 500;
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .hero .description {
            font-size: 1.2rem;
            margin-bottom: 35px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 8px rgba(0, 0, 0, 0.3);
            line-height: 1.6;
            opacity: 0.95;
        }

        .hero .btn-group {
            animation: fadeInScale 0.6s ease-out 0.2s forwards;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        .btn {
            display: inline-block;
            padding: 14px 34px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background-color: #4caf50;
            color: white;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-primary:hover {
            background-color: #3e8e41;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 25px rgba(76, 175, 80, 0.4);
            animation: borderGlow 0.8s ease-out;
        }

        .btn-secondary {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-4px) scale(1.02);
            border-color: #4caf50;
        }

        /* Container dan Heading */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 70px 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 55px;
            font-size: 2.3rem;
            font-weight: 700;
            position: relative;
        }

        h2.visible-title {
            animation: fadeInScale 0.5s ease forwards;
        }

        h2:after {
            content: '';
            display: block;
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, #2c3e50, #4caf50);
            margin: 18px auto 0;
            border-radius: 4px;
            transition: width 0.4s;
        }

        h2:hover:after {
            width: 110px;
        }

        /* Grid Cards - menggunakan float untuk IE */
        .grid {
            overflow: hidden;
            margin: 0 -16px;
        }

        .grid-item {
            width: 33.333%;
            float: left;
            padding: 0 16px;
            margin-bottom: 32px;
        }

        .card {
            background: white;
            padding: 35px 20px;
            border-radius: 24px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.35s ease;
            cursor: default;
            position: relative;
            overflow: hidden;
        }

        .card.visible-card {
            animation: fadeInScale 0.5s ease forwards;
        }

        .card:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.12);
        }

        .card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #4caf50, #2c3e50, #4caf50);
            background-size: 200% auto;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s;
        }

        .card:hover::after {
            transform: scaleX(1);
            animation: shimmer 1.2s infinite linear;
        }

        .card .number {
            width: 60px;
            height: 60px;
            background: #2c3e50;
            color: white;
            border-radius: 20px;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 auto 20px;
            transition: all 0.35s;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card:hover .number {
            background: #4caf50;
            border-radius: 30px;
            transform: rotate(5deg) scale(1.08);
        }

        .card h3 {
            font-size: 1.5rem;
            margin-bottom: 12px;
            color: #2c3e50;
        }

        .card p {
            color: #5a6e7a;
            line-height: 1.6;
            font-size: 1rem;
        }

        .card small {
            display: inline-block;
            margin-top: 12px;
            color: #4caf50;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Jadwal Cards styling */
        .jadwal-card .number {
            background: #e67e22;
            font-size: 2rem;
            border-radius: 50%;
        }

        .jadwal-card:hover .number {
            background: #f39c12;
            transform: scale(1.1) rotate(0deg);
        }

        /* Clearfix untuk float */
        .clearfix {
            clear: both;
        }

        /* ========== KONTAK PMB - VERSI 1: GRID 2 KOLOM ========== */
        .kontak-wrapper {
            overflow: hidden;
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s;
        }

        .kontak-wrapper.visible-kontak {
            animation: fadeInScale 0.6s ease forwards;
        }

        .kontak-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 28px 45px rgba(0, 0, 0, 0.12);
        }

        /* Bagian Kiri - Info Kontak */
        .kontak-left {
            width: 50%;
            float: left;
            padding: 40px 30px;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        /* Bagian Kanan - Maps Kotak */
        .kontak-right {
            width: 50%;
            float: left;
            background: #2c3e50;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .kontak-header {
            margin-bottom: 35px;
        }

        .kontak-header h3 {
            font-size: 1.6rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .kontak-header p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .kontak-list {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .kontak-list-item {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            gap: 18px;
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            transition: all 0.3s;
        }

        .kontak-list-item:hover {
            transform: translateX(5px);
            border-bottom-color: #4caf50;
        }

        .kontak-icon-box {
            width: 50px;
            height: 50px;
            background: #eef2f7;
            border-radius: 16px;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 500;
            color: #2c3e50;
            transition: all 0.3s;
        }

        .kontak-list-item:hover .kontak-icon-box {
            background: #4caf50;
            color: white;
        }

        .kontak-detail {
            -ms-flex: 1;
            flex: 1;
        }

        .kontak-detail .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #95a5a6;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .kontak-detail .value {
            font-size: 1.05rem;
            font-weight: 500;
            color: #2c3e50;
        }

        .kontak-detail .value a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s;
        }

        .kontak-detail .value a:hover {
            color: #4caf50;
        }

        .kontak-button {
            margin-top: 35px;
        }

        .btn-contact {
            display: inline-block;
            background: #2c3e50;
            color: white;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-contact:hover {
            background: #4caf50;
            transform: translateY(-2px);
        }

        .maps-header {
            background: #1a252f;
            padding: 18px 20px;
            text-align: center;
        }

        .maps-header h4 {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .maps-header p {
            color: #bdc3c7;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .maps-container {
            -ms-flex: 1;
            flex: 1;
            min-height: 320px;
        }

        .maps-container iframe {
            width: 100%;
            height: 320px;
            display: block;
            border: none;
        }

        .maps-footer {
            background: #1a252f;
            padding: 12px 20px;
            text-align: center;
        }

        .maps-footer a {
            color: #4caf50;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .maps-footer a:hover {
            color: white;
            text-decoration: underline;
        }

        /* ========== FOOTER PROFESIONAL ========== */
        footer {
            background: #1a2a3a;
            color: #ccddee;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 30px 30px;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-col {
            width: 33.333%;
            float: left;
            padding: 0 15px;
        }

        .footer-col h4 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .footer-col h4:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background: #4caf50;
            border-radius: 2px;
        }

        .footer-col p {
            line-height: 1.7;
            font-size: 0.9rem;
            opacity: 0.85;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 12px;
        }

        .footer-col ul li a {
            color: #ccddee;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .footer-col ul li a:hover {
            color: #4caf50;
            padding-left: 5px;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            text-align: center;
            line-height: 36px;
            margin-right: 10px;
            transition: all 0.3s;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .social-icons a:hover {
            background: #4caf50;
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding: 20px;
            font-size: 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: #0f1a24;
        }

        .footer-bottom p {
            opacity: 0.7;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .kontak-left, .kontak-right {
                width: 100%;
                float: none;
            }
            .maps-container iframe {
                height: 280px;
            }
            .grid-item {
                width: 50%;
            }
            .footer-col {
                width: 100%;
                float: none;
                text-align: center;
                margin-bottom: 30px;
            }
            .footer-col h4:after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
                -ms-flex-direction: column;
                flex-direction: column;
            }
            .nav-links a {
                margin: 0 8px;
            }
            .hero h1 {
                font-size: 2.4rem;
            }
            .hero .subtitle {
                font-size: 1.2rem;
            }
            .hero .description {
                font-size: 1rem;
            }
            .hero {
                padding: 80px 20px;
            }
            .btn {
                padding: 10px 20px;
                margin: 8px;
            }
            .container {
                padding: 45px 15px;
            }
            h2 {
                font-size: 1.8rem;
            }
            .grid-item {
                width: 100%;
                float: none;
            }
            .kontak-list-item {
                gap: 12px;
            }
            .kontak-icon-box {
                width: 42px;
                height: 42px;
                font-size: 1.2rem;
            }
        }

        
        .kontak-list-item {
            margin-bottom: 0;
        }
        .kontak-list-item .kontak-icon-box {
            margin-right: 18px;
        }
    </style>
</head>
<body>

   
    <div class="navbar-spacer"></div>

    <nav class="navbar" id="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo Mandala Cita" onerror="this.style.display='none'">
            PMB Mandala Cita
        </div>
        <div class="nav-links">
            <a href="#beranda">Beranda</a>
            <a href="#alur">Alur</a>
            <a href="#jadwal">Jadwal</a>
            <a href="#kontak">Kontak</a>
            <a href="login.php">Login</a>
            <a href="regist.php">Daftar</a>
        </div>
    </nav>

    
    <div class="hero" id="beranda">
        <h1>Selamat Datang</h1>
        <div class="subtitle">Menghimpun Cita, Mengarahkan Masa Depan</div>
        <div class="description">Mandala Cita adalah ruang bertemunya cita-cita besar untuk tumbuh, belajar, dan mencapai tujuan bersama.</div>
        <div class="btn-group">
            <a href="regist.php" class="btn btn-primary">Daftar Sekarang</a>
            <a href="login.php" class="btn btn-secondary">Login Peserta</a>
        </div>
    </div>

    
    <div class="container" id="alur">
        <h2 class="section-title">Alur Pendaftaran</h2>
        <div class="grid">
            <div class="grid-item"><div class="card"><div class="number">1</div><h3>Registrasi Akun</h3><p>Daftar akun baru dan dapatkan nomor peserta tes secara otomatis</p></div></div>
            <div class="grid-item"><div class="card"><div class="number">2</div><h3>Ujian Online</h3><p>Login dan ikuti seleksi ujian berbasis komputer dari rumah</p></div></div>
            <div class="grid-item"><div class="card"><div class="number">3</div><h3>Hasil Seleksi</h3><p>Lihat pengumuman kelulusan dan nilai ujian secara realtime</p></div></div>
            <div class="grid-item"><div class="card"><div class="number">4</div><h3>Daftar Ulang</h3><p>Lakukan verifikasi data dan unggah berkas persyaratan</p></div></div>
            <div class="grid-item"><div class="card"><div class="number">5</div><h3>Verifikasi Admin</h3><p>Tim PMB memverifikasi kelengkapan dan menerbitkan NIM</p></div></div>
            <div class="grid-item"><div class="card"><div class="number">6</div><h3>Mahasiswa Resmi</h3><p>Bergabung menjadi keluarga besar Mandala Cita</p></div></div>
            <div class="clearfix"></div>
        </div>
    </div>

   
    <div class="container" id="jadwal">
        <h2 class="section-title">Jadwal Penting</h2>
        <div class="grid">
            <div class="grid-item"><div class="card jadwal-card"><div class="number">📅</div><h3>Pendaftaran</h3><p>1 - 30 Maret 2026</p><small>Gelombang 1</small></div></div>
            <div class="grid-item"><div class="card jadwal-card"><div class="number">📝</div><h3>Tes Online</h3><p>5 - 10 April 2026</p><small>Jadwal fleksibel</small></div></div>
            <div class="grid-item"><div class="card jadwal-card"><div class="number">📢</div><h3>Pengumuman</h3><p>15 April 2026</p><small>Pukul 14.00 WIB</small></div></div>
            <div class="grid-item"><div class="card jadwal-card"><div class="number">📎</div><h3>Daftar Ulang</h3><p>20 - 25 April 2026</p><small>Upload berkas</small></div></div>
            <div class="clearfix"></div>
        </div>
    </div>

    
    <div class="container" id="kontak">
        <h2 class="section-title">Kontak PMB</h2>
        <div class="kontak-wrapper" id="kontakWrapper">
            <div class="kontak-left">
                <div class="kontak-header">
                    <h3>Hubungi Kami</h3>
                    <p>Tim PMB siap membantu proses pendaftaran Anda</p>
                </div>
                <div class="kontak-list">
                    <div class="kontak-list-item">
                        <div class="kontak-icon-box">📧</div>
                        <div class="kontak-detail">
                            <div class="label">Email Resmi</div>
                            <div class="value"><a href="mailto:pmb@mandalacita.ac.id">pmb@mandalacita.ac.id</a></div>
                        </div>
                    </div>
                    <div class="kontak-list-item">
                        <div class="kontak-icon-box">📱</div>
                        <div class="kontak-detail">
                            <div class="label">WhatsApp</div>
                            <div class="value"><a href="https://wa.me/6289123456789" target="_blank">0891-2345-6789</a></div>
                        </div>
                    </div>
                    <div class="kontak-list-item">
                        <div class="kontak-icon-box">🕘</div>
                        <div class="kontak-detail">
                            <div class="label">Jam Layanan</div>
                            <div class="value">Senin - Jumat, 08.00 - 16.00 WIB</div>
                        </div>
                    </div>
                    <div class="kontak-list-item">
                        <div class="kontak-icon-box">📍</div>
                        <div class="kontak-detail">
                            <div class="label">Alamat Kampus</div>
                            <div class="value">Jl. Mandala No. 1, Jakarta Timur</div>
                        </div>
                    </div>
                </div>
                <div class="kontak-button">
                    <a href="https://wa.me/6289123456789" class="btn-contact">💬 Konsultasi via WhatsApp</a>
                </div>
            </div>
            <div class="kontak-right">
                <div class="maps-header">
                    <h4>Lokasi Kampus Mandala Cita</h4>
                    <p>Jl. Mandala No. 1, Jakarta Timur</p>
                </div>
                <div class="maps-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521297362486!2d106.86534851476935!3d-6.200229995515215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f391cafe6c6b%3A0x5b6c7c7e5f8a2c3a!2sJakarta%20Timur%2C%20Jakarta%2C%20Indonesia!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Google Maps - Lokasi Kampus Mandala Cita">
                    </iframe>
                </div>
                <div class="maps-footer">
                    <a href="https://maps.google.com/?q=Jl.+Mandala+No.+1+Jakarta+Timur" target="_blank">Lihat rute & petunjuk arah →</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    
    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h4>PMB Mandala Cita</h4>
                <p>Membuka pintu masa depan melalui pendidikan berkualitas. Proses penerimaan mahasiswa baru yang transparan dan akuntabel.</p>
                <div class="social-icons">
                    <a href="#">FB</a>
                    <a href="#">IG</a>
                    <a href="#">TW</a>
                    <a href="#">LI</a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Informasi</h4>
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#alur">Alur Pendaftaran</a></li>
                    <li><a href="#jadwal">Jadwal Penting</a></li>
                    <li><a href="#kontak">Kontak Kami</a></li>
                    <li><a href="login.php">Login Peserta</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kontak Cepat</h4>
                <p>pmb@mandalacita.ac.id</p>
                <p>0878-5370-4191</p>
                <p>Senin - Jumat, 08.00 - 16.00</p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Penerimaan Mahasiswa Baru Mandala Cita. All Rights Reserved. | Sistem Informasi PMB Terintegrasi</p>
        </div>
    </footer>

    <script>
       
        var navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 60) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Fungsi cek elemen visible
        function isElementInViewport(el) {
            var rect = el.getBoundingClientRect();
            var windowHeight = window.innerHeight || document.documentElement.clientHeight;
            return (rect.top < windowHeight - 100 && rect.bottom > 50);
        }

        // Fungsi animasi scroll
        function checkVisibility() {
            // Cek titles
            var titles = document.querySelectorAll('.section-title');
            for (var i = 0; i < titles.length; i++) {
                if (isElementInViewport(titles[i]) && !titles[i].classList.contains('visible-title')) {
                    titles[i].classList.add('visible-title');
                }
            }
            
            // Cek cards
            var cards = document.querySelectorAll('.card');
            for (var i = 0; i < cards.length; i++) {
                if (isElementInViewport(cards[i]) && !cards[i].classList.contains('visible-card')) {
                    cards[i].classList.add('visible-card');
                }
            }
            
            // Cek kontak wrapper
            var kontak = document.getElementById('kontakWrapper');
            if (kontak && isElementInViewport(kontak) && !kontak.classList.contains('visible-kontak')) {
                kontak.classList.add('visible-kontak');
            }
        }

        // Smooth scroll untuk anchor links
        var links = document.querySelectorAll('.nav-links a[href^="#"]');
        for (var i = 0; i < links.length; i++) {
            links[i].addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('href');
                if (targetId === '#') return;
                var targetElement = document.querySelector(targetId);
                if (targetElement) {
                    var offset = 80;
                    var targetPosition = targetElement.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        }

        // Hover effect numbers
        var numbers = document.querySelectorAll('.card .number');
        for (var i = 0; i < numbers.length; i++) {
            numbers[i].addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.12) translateY(-5px)';
            });
            numbers[i].addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0)';
            });
        }

        // Initial check
        window.addEventListener('load', function() {
            checkVisibility();
        });
        window.addEventListener('scroll', function() {
            checkVisibility();
        });
        setTimeout(checkVisibility, 100);
    </script>
</body>
</html>