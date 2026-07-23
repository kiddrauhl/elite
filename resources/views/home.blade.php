<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite English Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1e3a8a; /* Blue-900 */
            --secondary: #3b82f6; /* Blue-500 */
            --accent: #f59e0b; /* Amber-500 */
            --dark: #0f172a; /* Slate-900 */
            --text-muted: #64748b; /* Slate-500 */
            --bg-light: #f8fafc; /* Slate-50 */
            --surface: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--dark);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* 1. NAVBAR PROFESIONAL */
        /* 1. NAVBAR PROFESIONAL */
        .navbar {
            background-color: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: #ffffff !important; letter-spacing: -0.5px; }
        .navbar-brand span { color: var(--accent); }
        .nav-link { font-weight: 600; font-size: 0.95rem; color: rgba(255, 255, 255, 0.8) !important; margin: 0 12px; transition: color 0.2s ease; }
        .nav-link:hover { color: var(--accent) !important; }

        .btn-nav-login {
            background-color: var(--accent); color: var(--dark) !important; font-weight: 700;
            padding: 8px 24px; border-radius: 8px; border: none; transition: all 0.2s;
        }
        .btn-nav-login:hover { background-color: #d97706; color: white !important; }

        /* 2. HERO SECTION */
        .hero-premium {
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(30, 58, 138, 0.85)),
                        url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            padding: 220px 0 160px 0; text-align: center; color: white; position: relative;
        }
        .hero-title { font-size: 4rem; font-weight: 800; letter-spacing: -1px; line-height: 1.1; margin-bottom: 24px; }
        .hero-title span { color: var(--accent); }
        .hero-sub { font-size: 1.15rem; font-weight: 400; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto 40px auto; line-height: 1.6; }
        .btn-hero-primary {
            background-color: var(--accent); color: var(--dark); font-weight: 700; padding: 14px 32px;
            border-radius: 8px; text-decoration: none; transition: all 0.2s; display: inline-block; margin: 0 8px 10px 8px;
        }
        .btn-hero-primary:hover { background-color: #d97706; color: white; }
        .btn-hero-secondary {
            background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px); color: white; font-weight: 700;
            padding: 14px 32px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.2); text-decoration: none;
            transition: all 0.2s; display: inline-block; margin: 0 8px 10px 8px;
        }
        .btn-hero-secondary:hover { background-color: rgba(255, 255, 255, 0.2); color: white; }

        /* 3. SECTION PADDING & TITLE */
        .section-padding { padding: 100px 0; position: relative; overflow: hidden; }
        .section-title { font-weight: 800; font-size: 2.25rem; color: var(--dark); text-align: center; margin-bottom: 16px; letter-spacing: -0.5px; }
        .section-subtitle { text-align: center; color: var(--text-muted); font-size: 1.1rem; margin-bottom: 60px; }

        /* 🌟 4. KONTRAST DARK MODE (Baru) */
        .section-dark { background-color: #0f172a; }
        .section-dark .section-title { color: #ffffff; }
        .section-dark .section-subtitle { color: #94a3b8; }

        /* 🌟 5. DEKORASI BACKGROUND (Baru) */
        .bg-glow { position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.4; z-index: 0; pointer-events: none; }
        .glow-blue { width: 300px; height: 300px; background-color: #3b82f6; top: -50px; left: -100px; }
        .glow-amber { width: 300px; height: 300px; background-color: #f59e0b; bottom: -50px; right: -100px; }

        /* 🌟 6. CARDS PROFESIONAL YANG DIPERBARUI */
        .pro-card {
            background: var(--surface);
            border: none; /* Hilangkan border kaku */
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); /* Bayangan lebih soft */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            z-index: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        /* Animasi Garis Atas saat Hover */
        .pro-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 4px;
            transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease;
        }
        .pro-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        }
        .pro-card:hover::before { transform: scaleX(1); }

        /* Warna spesifik garis */
        .border-step-1::before { background-color: #3b82f6; }
        .border-step-2::before { background-color: #f59e0b; }
        .border-step-3::before { background-color: #10b981; }
        .border-step-4::before { background-color: #8b5cf6; }

        .clickable-card { cursor: pointer; }

        .icon-box {
            width: 56px; height: 56px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 24px;
            background-color: var(--bg-light); color: var(--primary);
        }
        .lvl-1 .icon-box { background-color: #eff6ff; color: #2563eb; }
        .lvl-2 .icon-box { background-color: #fef3c7; color: #d97706; }
        .lvl-3 .icon-box { background-color: #ecfdf5; color: #059669; }
        .lvl-4 .icon-box { background-color: #f3e8ff; color: #7c3aed; }

        /* 🌟 7. KARTU FASILITAS (Baru) */
        .fasilitas-card {
            background: white; border-radius: 16px; padding: 24px;
            border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s ease; height: 100%;
        }
        .fasilitas-card:hover { background: #f8fafc; border-color: rgba(59, 130, 246, 0.2); transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.03); }
        .fac-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }

        /* 5. MODAL PROFESIONAL */
        .modal-content {
            border: none; border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;
        }
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.05); padding: 24px 32px; background-color: var(--bg-light);
        }
        .modal-body { padding: 32px; }
        .price-box {
            background-color: var(--bg-light); border: 1px solid rgba(0,0,0,0.05);
            padding: 16px 24px; border-radius: 12px; display: inline-block; margin-top: 16px;
        }
        .price-box span { font-size: 1.5rem; font-weight: 800; color: var(--dark); }

        /* 🌟 6. CTA BANNER BARU (Pemecah Kebosanan) */
        .cta-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            padding: 70px 0;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-banner::before {
            content: ''; position: absolute;
            top: -50%; left: -5%;
            width: 300px; height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        .cta-banner::after {
            content: ''; position: absolute;
            bottom: -50%; right: -5%;
            width: 400px; height: 400px;
            background: rgba(245, 158, 11, 0.05);
            border-radius: 50%;
        }
        .cta-title { font-size: 2.25rem; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 16px; }

        /* 🌟 7. FOOTER DARK MODE PREMIUM */
        .footer-pro {
            background-color: #0b1121;
            padding: 80px 0 30px 0;
            color: #94a3b8;
        }
        .footer-title {
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 24px;
            font-size: 1.25rem;
        }
        .footer-pro a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
        .footer-pro a:hover { color: var(--accent); }
        .social-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            margin-right: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }
        .social-circle:hover { background-color: var(--accent); color: var(--dark); border-color: var(--accent); }
        .footer-bottom { border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 24px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" height="40" class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#alur">Alur Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link" href="#level">Program Kelas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fasilitas">Fasilitas</a></li>
                </ul>
                <div class="d-flex mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('login') }}" class="btn btn-nav-login">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-nav-login">Login</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-premium">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center">
                    <h1 class="hero-title">Kuasai Bahasa,<br>Raih <span>Masa Depanmu.</span></h1>
                    <p class="hero-sub">Wujudkan prestasi akademik terbaikmu dengan metode pembelajaran interaktif, pengajar profesional, dan fasilitas modern di Elite English Course.</p>
                    <div class="mt-4">
                        @if($gelombangAktif->count() > 0)
                            <a href="/register" class="btn-hero-primary">Daftar Sekarang</a>
                        @else
                            <button disabled class="btn-hero-primary" style="background-color: #94a3b8; border-color: #94a3b8; color: #ffffff; cursor: not-allowed; opacity: 0.8;">
                                <i class="fa-solid fa-lock me-2"></i> Pendaftaran Ditutup
                            </button>
                        @endif

                        <a href="#alur" class="btn-hero-secondary">Pelajari Alur</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jadwal-pendaftaran" class="py-5" style="background-color: #f8fafc;">
        <div class="container py-4">

            <div class="text-center mb-5">
                <span class="text-uppercase fw-bold" style="color: #f59e0b; letter-spacing: 2px; font-size: 0.85rem;">Informasi Pendaftaran</span>
                <h2 class="fw-bold mt-2" style="color: #0f172a;">Gelombang Tersedia Saat Ini</h2>
            </div>

            @if($gelombangAktif->count() > 0)
                <div class="row justify-content-center g-4">
                    @foreach($gelombangAktif as $g)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm" style="border-radius: 1.25rem; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #eff6ff; color: #3b82f6;">
                                            <i class="fa-solid fa-graduation-cap fs-5"></i>
                                        </div>
                                        <span class="badge rounded-pill d-flex align-items-center" style="background-color: #ecfdf5; color: #059669; border: 1px solid #10b981; padding: 6px 12px; font-weight: 700; font-size: 0.75rem;">
                                            <span class="rounded-circle me-2" style="width: 6px; height: 6px; background-color: #10b981; animation: pulse-green 2s infinite;"></span> Buka
                                        </span>
                                    </div>

                                    <h4 class="fw-bold mb-3" style="color: #1e293b;">{{ $g->nama_gelombang }}</h4>

                                    <div class="d-flex align-items-center text-muted mb-4 pb-2" style="font-size: 0.95rem; border-bottom: 1px dashed #e2e8f0;">
                                        <i class="fa-regular fa-calendar-check text-primary me-3 fs-5"></i>
                                        <div>
                                            <small class="d-block text-uppercase fw-bold" style="font-size: 0.7rem; color: #94a3b8;">Jadwal Pelaksanaan</small>
                                            {{ \Carbon\Carbon::parse($g->tanggal_mulai)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($g->tanggal_selesai)->translatedFormat('d M Y') }}
                                        </div>
                                    </div>

                                    <a href="/register" class="btn w-100 fw-bold" style="background-color: #f8fafc; color: #3b82f6; border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 0.75rem; transition: all 0.2s;">
                                        Pilih Gelombang Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center p-5 rounded-4 shadow-sm mx-auto" style="max-width: 600px; background-color: white;">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; background-color: #fef2f2; color: #ef4444;">
                        <i class="fa-solid fa-lock fs-1"></i>
                    </div>
                    <h4 class="fw-bold mt-2" style="color: #0f172a;">Pendaftaran Sedang Ditutup</h4>
                    <p class="text-muted mb-0">Mohon maaf, saat ini belum ada gelombang pendaftaran baru yang dibuka. Silakan pantau terus website kami untuk informasi selanjutnya.</p>
                </div>
            @endif

        </div>
    </section>

    <style>
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        #jadwal-pendaftaran .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        }
        #jadwal-pendaftaran .btn:hover {
            background-color: #eff6ff !important;
        }
    </style>

    <section id="alur" class="section-padding bg-white position-relative z-1">
        <!-- Efek Glow Background -->
        <div class="bg-glow glow-blue"></div>
        <div class="bg-glow glow-amber"></div>

        <div class="container position-relative z-1">
            <h2 class="section-title">Alur Pendaftaran</h2>
            <p class="section-subtitle">Proses penyaringan dirancang transparan untuk menempatkan siswa di kelas yang tepat.</p>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="pro-card border-step-1">
                        <div class="text-primary fw-bold mb-3 small tracking-wider" style="letter-spacing: 1px;">01. REGISTRASI</div>
                        <h5 class="fw-bold mb-3 text-dark">Buat Akun</h5>
                        <p class="text-muted small mb-0 flex-grow-1" style="line-height: 1.6;">Pendaftar menginput biodata pribadi dan membuat akun untuk akses Elite Student.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pro-card border-step-2">
                        <div class="text-warning fw-bold mb-3 small tracking-wider" style="letter-spacing: 1px;">02. PLACEMENT TEST</div>
                        <h5 class="fw-bold mb-3 text-dark">Uji Level</h5>
                        <p class="text-muted small mb-0 flex-grow-1" style="line-height: 1.6;">Siswa mengikuti placement test secara online untuk mengukur kompetensi dasar.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pro-card border-step-3">
                        <div class="text-success fw-bold mb-3 small tracking-wider" style="letter-spacing: 1px;">03. EVALUASI</div>
                        <h5 class="fw-bold mb-3 text-dark">Review Pengajar</h5>
                        <p class="text-muted small mb-0 flex-grow-1" style="line-height: 1.6;">Pengajar memeriksa hasil tes dan melakukan analisis penempatan level yang sesuai.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pro-card border-step-4">
                        <div style="color: #8b5cf6;" class="fw-bold mb-3 small tracking-wider">04. PENEMPATAN</div>
                        <h5 class="fw-bold mb-3 text-dark">Penempatan Kelas</h5>
                        <p class="text-muted small mb-0 flex-grow-1" style="line-height: 1.6;">Pendaftar secara resmi ditempatkan di 1 dari 4 tingkatan kelas yang sesuai kemampuannya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION LEVEL: DIUBAH MENJADI DARK MODE -->
    <section id="level" class="section-padding section-dark">
        <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; height: 100%; background: radial-gradient(circle at top, rgba(30, 58, 138, 0.4) 0%, transparent 60%); pointer-events: none;"></div>

        <div class="container position-relative z-1">
            <h2 class="section-title">Struktur Kurikulum Level</h2>
            <p class="section-subtitle">Pilih salah satu di bawah ini untuk melihat detail biaya dan fasilitas masing-masing level.</p>

            <div class="row g-4 mt-2">
                <div class="col-lg-3 col-md-6" data-bs-toggle="modal" data-bs-target="#modalLevel1">
                    <div class="pro-card clickable-card lvl-1 border-step-1">
                        <div class="icon-box"><i class="fa-solid fa-layer-group"></i></div>
                        <h4 class="fw-bold text-dark mb-2">Beginner</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Membangun pemahaman dari dasar, memperkuat konsep dasar, dan menumbuhkan rasa percaya diri untuk belajar.</p>
                        <div class="mt-auto border-top pt-3">
                            <span class="text-primary fw-bold small"><i class="fa-solid fa-arrow-right me-2"></i>Lihat Detail</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-bs-toggle="modal" data-bs-target="#modalLevel2">
                    <div class="pro-card clickable-card lvl-2 border-step-2">
                        <div class="icon-box"><i class="fa-solid fa-book-open"></i></div>
                        <h4 class="fw-bold text-dark mb-2">Intermediate</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Mengembangkan materi dasar ke tingkat yang lebih kompleks dan melatih variasi penyelesaian masalah.</p>
                        <div class="mt-auto border-top pt-3">
                            <span class="text-warning fw-bold small"><i class="fa-solid fa-arrow-right me-2"></i>Lihat Detail</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-bs-toggle="modal" data-bs-target="#modalLevel3">
                    <div class="pro-card clickable-card lvl-3 border-step-3">
                        <div class="icon-box"><i class="fa-solid fa-chart-line"></i></div>
                        <h4 class="fw-bold text-dark mb-2">Advanced</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Diselaraskan dengan pelajaran sekolah untuk memastikan siswa siap dan unggul dalam setiap ujian harian.</p>
                        <div class="mt-auto border-top pt-3">
                            <span class="text-success fw-bold small"><i class="fa-solid fa-arrow-right me-2"></i>Lihat Detail</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-bs-toggle="modal" data-bs-target="#modalLevel4">
                    <div class="pro-card clickable-card lvl-4 border-step-4">
                        <div class="icon-box"><i class="fa-solid fa-crown"></i></div>
                        <h4 class="fw-bold text-dark mb-2">Expert Master</h4>
                        <p class="text-muted small mb-4 flex-grow-1">Tantangan tingkat lanjut dan pemecahan soal berpikir kritis untuk persiapan kompetisi maupun Test TOEFL.</p>
                        <div class="mt-auto border-top pt-3">
                            <span style="color: #7c3aed;" class="fw-bold small"><i class="fa-solid fa-arrow-right me-2"></i>Lihat Detail</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION FASILITAS: DESAIN GRID KARTU BARU -->
    <section id="fasilitas" class="section-padding" style="background-color: #f8fafc;">
        <div class="container">
            <h2 class="section-title">Fasilitas Tempat Belajar</h2>
            <p class="section-subtitle">Dukungan infrastruktur modern untuk kenyamanan maksimal saat belajar.</p>

            <div class="row g-4 mt-2">
                <div class="col-lg-3 col-md-6">
                    <div class="fasilitas-card">
                        <div class="fac-icon" style="background-color: #eff6ff; color: #3b82f6;">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Kelas Nyaman</h5>
                        <p class="text-muted small mb-0">Ruangan Full AC dilengkapi dengan proyektor interaktif modern.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="fasilitas-card">
                        <div class="fac-icon" style="background-color: #fef2f2; color: #ef4444;">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Movie Class</h5>
                        <p class="text-muted small mb-0">Pembelajaran *listening* audiovisual menggunakan metode *movie screening*.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="fasilitas-card">
                        <div class="fac-icon" style="background-color: #ecfdf5; color: #10b981;">
                            <i class="fa-solid fa-book-bookmark"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Perpustakaan</h5>
                        <p class="text-muted small mb-0">Akses literasi lengkap dan ruang khusus untuk belajar mandiri yang hening.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="fasilitas-card">
                        <div class="fac-icon" style="background-color: #fef3c7; color: #f59e0b;">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Konsultasi PR</h5>
                        <p class="text-muted small mb-0">Layanan bimbingan khusus di luar jam kelas untuk menuntaskan PR sekolah.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="container position-relative z-1">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Siap Memulai Perjalanan Belajarmu?</h2>
                    <p class="mb-4 text-white-50 fs-6">Bergabunglah dengan ratusan siswa lainnya yang telah membuktikan keunggulan metode belajar interaktif bersama instruktur kami.</p>
                    <div class="mt-4">
                        @if($gelombangAktif->count() > 0)
                            <a href="/register" class="btn-hero-primary">Daftar Sekarang</a>
                        @else
                            <button disabled class="btn-hero-primary" style="background-color: #94a3b8; border-color: #94a3b8; color: #ffffff; cursor: not-allowed; opacity: 0.8;">
                                <i class="fa-solid fa-lock me-2"></i> Pendaftaran Ditutup
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-pro">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-4 col-md-6">
                    <a class="navbar-brand d-flex align-items-center" href="/">
                        <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" height="40" class="d-inline-block align-top">
                    </a>

                    <p class="small mb-4 pe-lg-4" style="line-height: 1.7;">Sistem manajemen bimbingan belajar untuk mengoptimalkan kemampuan bahasa inggris dan membangun generasi berprestasi.</p>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Kontak & Bantuan</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-3 d-flex align-items-start"><i class="fa-solid fa-location-dot mt-1 me-3 text-secondary"></i> Jl. Ahmad Yani Km. 3,5 70234 Banjarmasin Timur, Kalimantan Selatan</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fa-solid fa-phone me-3 text-secondary"></i> (0511) 6790378</li>
                        <li class="mb-3 d-flex align-items-center"><i class="fa-solid fa-envelope me-3 text-secondary"></i> info@elitenglish.id</li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h5 class="footer-title">Lokasi Bimbel</h5>
                    <div style="border-radius:12px; overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
                        <iframe
                            src="https://maps.google.com/maps?q=-3.329656, 114.610494&z=17&output=embed"
                            width="100%"
                            height="150"
                            style="border:0; display:block;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="footer-bottom text-center">
                <p class="small mb-0">© {{ date('Y') }} Elite English Course. Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="modalLevel1" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-layer-group text-primary me-2"></i> Beginner Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Program (Level Beginner)</h6>
                        <p class="text-muted small mb-4">
                            Program ini dirancang untuk tingkat pemula. Setelah menyelesaikan level ini, siswa diharapkan mampu memperkenalkan diri secara percaya diri, menyusun kalimat sederhana (Simple Present Tense), dan memahami instruksi dasar berbahasa Inggris.
                        </p>

                        <h6 class="fw-bold text-dark mb-2">Detail Pelaksanaan</h6>
                        <ul class="text-muted small ps-3 mb-4">
                            <li><strong>Durasi Level:</strong> 3 Bulan</li>
                            <li><strong>Total Pertemuan:</strong> 24 Pertemuan (2x Seminggu)</li>
                            <li><strong>Jadwal Kelas:</strong> [Pukul 15.00 - 16.30 WITA]</li>
                        </ul>

                        <h6 class="fw-bold text-dark mb-2">Fasilitas</h6>
                        <ul class="text-muted small ps-3">
                            <li>Buku Materi Bahasa Inggris</li>
                            <li>Materi Tiap Pertemuan</li>
                            <li>Sertifikat Kelulusan</li>
                        </ul>
                    <div class="price-box w-100 text-center">
                        <span class="d-block text-muted small fw-normal mb-1">Biaya</span>
                        <span class="text-primary">Rp 2.150.000</span> <small class="text-muted fw-normal">/ Level</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLevel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-book-open text-warning me-2"></i> Intermediate Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Program (Level Intermediate)</h6>
                        <p class="text-muted small mb-4">
                            Mengembangkan kemampuan komunikasi mandiri melalui penguatan struktur kalimat dan peningkatan kemampuan <em>listening</em>. Siswa dilatih agar lancar melakukan percakapan dua arah dan mampu mengekspresikan pendapat dengan baik dan terstruktur.
                        </p>

                        <h6 class="fw-bold text-dark mb-2">Detail Pelaksanaan</h6>
                        <ul class="text-muted small ps-3 mb-4">
                            <li><strong>Durasi Level:</strong> 3 Bulan</li>
                            <li><strong>Total Pertemuan:</strong> 24 Pertemuan (1-2x Seminggu)</li>
                            <li><strong>Jadwal Kelas:</strong> [Pukul 16.00 - 17.30 WITA]</li>
                        </ul>

                        <h6 class="fw-bold text-dark mb-2">Fasilitas</h6>
                        <ul class="text-muted small ps-3">
                            <li>Buku Materi Bahasa Inggris</li>
                            <li>Class Movie (Pembelajaran via Audio-Visual)</li>
                            <li>Conversation Class</li>
                            <li>Sertifikat Level Kelulusan</li>
                        </ul>
                    <div class="price-box w-100 text-center">
                        <span class="d-block text-muted small fw-normal mb-1">Biaya</span>
                        <span class="text-warning">Rp 2.150.000</span> <small class="text-muted fw-normal">/ Level</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLevel3" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-chart-line text-success me-2"></i> Regular Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Program (Level Regular)</h6>
                        <p class="text-muted small mb-4">
                            Fokus pada penguatan fondasi tata bahasa (grammar), penambahan kosakata (vocabulary), dan melatih kepercayaan diri siswa. Setelah menyelesaikan level ini, siswa diharapkan mampu merespons percakapan sehari-hari dengan kalimat yang lebih variatif.
                        </p>

                        <h6 class="fw-bold text-dark mb-2">Detail Pelaksanaan</h6>
                        <ul class="text-muted small ps-3 mb-4">
                            <li><strong>Durasi Level:</strong> 3 Bulan</li>
                            <li><strong>Total Pertemuan:</strong> 24 Pertemuan (2x Seminggu)</li>
                            <li><strong>Jadwal Kelas:</strong> [Pukul 15.00 - 16.30 WITA]</li>
                        </ul>

                        <h6 class="fw-bold text-dark mb-2">Fasilitas</h6>
                        <ul class="text-muted small ps-3">
                            <li>Buku Materi Bahasa Inggris (Modul)</li>
                            <li>Latihan Praktik Berbicara (Basic Speaking)</li>
                            <li>Sertifikat Level Kelulusan</li>
                        </ul>
                    <div class="price-box w-100 text-center">
                        <span class="d-block text-muted small fw-normal mb-1">Biaya</span>
                        <span class="text-success">Rp 2.150.000</span> <small class="text-muted fw-normal">/ Level</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLevel4" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-crown me-2" style="color: #7c3aed;"></i> Expert Level</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Program (Level Expert)</h6>
                        <p class="text-muted small mb-4">
                            Dirancang untuk penguasaan bahasa Inggris tingkat lanjut. Berfokus pada kemampuan diskusi kompleks, debat, presentasi, serta pemahaman teks dan audio analitis dengan tingkat akurasi tata bahasa (grammar) dan pelafalan (pronunciation) yang tinggi.
                        </p>

                        <h6 class="fw-bold text-dark mb-2">Detail Pelaksanaan</h6>
                        <ul class="text-muted small ps-3 mb-4">
                            <li><strong>Durasi Level:</strong> 3 Bulan</li>
                            <li><strong>Total Pertemuan:</strong> 24 Pertemuan (2x Seminggu)</li>
                            <li><strong>Jadwal Kelas:</strong> [Pukul 15.30 - 16.30 WITA]</li>
                        </ul>

                        <h6 class="fw-bold text-dark mb-2">Fasilitas</h6>
                        <ul class="text-muted small ps-3">
                            <li>Buku Materi Bahasa Inggris Lanjutan (Advanced)</li>
                            <li>Presentation Session</li>
                            <li>Movie Class</li>
                            <li>Sertifikat Level</li>
                        </ul>
                    <div class="price-box w-100 text-center">
                        <span class="d-block text-muted small fw-normal mb-1">Biaya</span>
                        <span style="color: #7c3aed; font-weight:800; font-size:1.5rem;">Rp 2.150.000</span> <small class="text-muted fw-normal">/ bulan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                nav.style.backgroundColor = 'rgba(15, 23, 42, 1)';
                nav.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            } else {
                nav.style.backgroundColor = 'rgba(15, 23, 42, 0.85)';
                nav.style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>
