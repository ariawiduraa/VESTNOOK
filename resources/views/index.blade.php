<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Cerdas Analisis Lahan - Rekomendasi pupuk presisi berbasis AI untuk petani Indonesia">
    <title>VESTNOOK - Sistem Cerdas Analisis Lahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --blue: #4285f4;
            --blue-light: #8ab4f8;
            --purple: #a142f4;
            --teal: #24c1e0;
            --green: #34a853;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #000; color: #fff; overflow-x: hidden; }

        /* ─── CANVAS ─── */
        #particle-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* ─── NAVBAR ─── */
        #navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            transition: background 0.3s, backdrop-filter 0.3s, border-color 0.3s;
        }
        #navbar.scrolled {
            background: rgba(10,10,15,0.82);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo-icon {
            width: 32px; height: 32px;
            background: conic-gradient(from 0deg, #4285f4, #34a853, #fbbc04, #ea4335, #4285f4);
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3l1.5 4.5H18l-3.5 2.5 1.5 4.5L12 14l-4 2.5 1.5-4.5L6 9.5h4.5L12 5z'/%3E%3C/svg%3E") no-repeat center / contain;
            border-radius: 6px;
        }
        .nav-logo span { font-size: 18px; font-weight: 500; color: #fff; letter-spacing: -0.02em; }
        .nav-links { display: flex; gap: 32px; }
        .nav-links a { font-size: 14px; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .btn-outline { padding: 8px 20px; font-size: 14px; font-weight: 500; color: #fff; border: 1px solid rgba(255,255,255,0.3); border-radius: 24px; text-decoration: none; transition: all .2s; }
        .btn-outline:hover { border-color: #fff; background: rgba(255,255,255,0.08); }
        .btn-primary { padding: 8px 20px; font-size: 14px; font-weight: 500; background: #4285f4; color: #fff; border-radius: 24px; text-decoration: none; transition: all .2s; }
        .btn-primary:hover { background: #3367d6; box-shadow: 0 0 24px rgba(66,133,244,0.4); }

        /* ─── HERO ─── */
        #hero {
            position: relative;
            height: 100vh;
            min-height: 700px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: radial-gradient(ellipse 80% 60% at 50% 50%, #0b1930 0%, #000 100%);
        }
        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 0 24px;
            max-width: 820px;
            pointer-events: none;
        }
        .hero-content a { pointer-events: auto; }
        .hero-eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--blue-light);
            margin-bottom: 24px;
            border: 1px solid rgba(138,180,248,0.25);
            padding: 5px 16px;
            border-radius: 24px;
            background: rgba(138,180,248,0.06);
        }
        .hero-title {
            font-family: 'Inter', sans-serif;
            font-size: clamp(36px, 6vw, 72px);
            font-weight: 500;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 20px;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, #8ab4f8, #c084fc, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: clamp(15px, 2vw, 18px);
            color: rgba(255,255,255,0.55);
            font-weight: 400;
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 32px;
            background: #4285f4;
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            border-radius: 100px;
            text-decoration: none;
            transition: all .25s;
            box-shadow: 0 0 40px rgba(66,133,244,0.35);
        }
        .btn-hero-primary:hover { background: #3367d6; transform: translateY(-2px); box-shadow: 0 8px 48px rgba(66,133,244,0.5); }
        .btn-hero-ghost {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 32px;
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            border-radius: 100px;
            text-decoration: none;
            transition: all .25s;
        }
        .btn-hero-ghost:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.05); }
        .hero-footer { position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); font-size: 12px; color: rgba(255,255,255,0.3); text-align: center; z-index: 10; }
        .scroll-arrow { animation: bounce 2s infinite; }
        @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(6px)} }

        /* ─── SECTIONS ─── */
        .section-white { background: #fff; color: #1a1a1a; padding: 96px 24px; }
        .section-dark  { background: #080e1c; color: #fff;   padding: 96px 24px; }
        .section-inner { max-width: 1100px; margin: 0 auto; }
        .section-label {
            font-size: 13px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
            color: #4285f4; margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(28px, 4vw, 42px);
            font-weight: 500; letter-spacing: -0.02em; line-height: 1.2; margin-bottom: 20px;
        }
        .section-sub { font-size: 16px; line-height: 1.8; color: #6b7280; max-width: 560px; }
        .section-dark .section-sub { color: rgba(255,255,255,0.5); }
        .learn-more { display: inline-flex; align-items: center; gap: 4px; color: #4285f4; font-weight: 500; font-size: 15px; text-decoration: none; margin-top: 20px; }
        .learn-more:hover { text-decoration: underline; }

        /* Two-col layout */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
        @media(max-width:768px){ .two-col { grid-template-columns: 1fr; gap: 32px; } }

        /* Visual box */
        .visual-box {
            width: 100%; aspect-ratio: 4/3;
            border-radius: 24px; overflow: hidden;
            position: relative;
        }
        .visual-box-inner {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #1a2460 0%, #0f3460 40%, #533483 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .visual-box-inner::after {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 40% 40%, rgba(66,133,244,0.3), transparent 60%),
                        radial-gradient(ellipse at 70% 70%, rgba(161,66,244,0.3), transparent 60%);
        }
        .visual-label { position: relative; z-index: 2; font-size: 16px; font-weight: 500; color: rgba(255,255,255,0.6); letter-spacing: 0.05em; }

        /* Steps cara pakai */
        .steps { display: flex; flex-direction: column; gap: 0; }
        .step { display: flex; gap: 24px; padding: 36px 0; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .section-dark .step { border-bottom-color: rgba(255,255,255,0.06); }
        .step:last-child { border-bottom: none; }
        .step-number {
            flex-shrink: 0;
            width: 48px; height: 48px;
            border-radius: 50%;
            background: #4285f4;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 700; color: #fff;
        }
        .step-body h3 { font-size: 20px; font-weight: 600; margin-bottom: 8px; }
        .step-body p { font-size: 15px; line-height: 1.75; color: #6b7280; }
        .section-dark .step-body p { color: rgba(255,255,255,0.5); }

        /* Fitur cards */
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 24px; margin-top: 56px; }
        .feature-card {
            padding: 32px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.03);
            transition: all .25s;
        }
        .feature-card:hover { border-color: rgba(66,133,244,0.4); background: rgba(66,133,244,0.05); transform: translateY(-4px); }
        .feature-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: rgba(66,133,244,0.15);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; color: #8ab4f8; font-size: 22px;
        }
        .feature-card h3 { font-size: 18px; font-weight: 600; margin-bottom: 10px; color: #fff; }
        .feature-card p { font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.75; }

        /* Stats */
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 32px; margin-top: 48px; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 48px; font-weight: 700; color: #4285f4; letter-spacing: -0.03em; }
        .stat-label { font-size: 14px; color: rgba(255,255,255,0.5); margin-top: 4px; }

        /* CTA Section */
        .cta-section {
            padding: 120px 24px;
            background: radial-gradient(ellipse 80% 80% at 50% 50%, #0b1930 0%, #000 100%);
            text-align: center;
        }
        .cta-section h2 { font-size: clamp(28px,5vw,52px); font-weight: 500; letter-spacing: -0.02em; margin-bottom: 16px; }
        .cta-section p { font-size: 17px; color: rgba(255,255,255,0.5); margin-bottom: 40px; }

        /* Footer */
        footer {
            background: #000;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 40px 24px;
            display: flex; flex-direction: column; align-items: center; gap: 16px;
            font-size: 13px; color: rgba(255,255,255,0.35);
        }

        @media(max-width:640px){
            .nav-links { display: none; }
            .hero-title { font-size: 32px; }
        }
    </style>
</head>
<body>

    <!-- ─── NAVBAR ─── -->
    <nav id="navbar">
        <a href="/" class="nav-logo">
            <img src="{{ asset('images/logovestnook.png') }}" alt="Logo VESTNOOK" style="width: 32px; height: 32px; object-fit: contain;">
            <span>VESTNOOK</span>
        </a>
        <div class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="#cara-pakai">Cara Pakai</a>
            <a href="#iot">IoT Sensor</a>
            <a href="#panduan">Panduan</a>
            <a href="#tentang">Tentang</a>
        </div>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="btn-outline">Daftar</a>
                <a href="{{ route('login') }}" class="btn-primary">Login</a>
            @endauth
        </div>
    </nav>

    <!-- ─── HERO SECTION ─── -->
    <section id="hero">
        <canvas id="particle-canvas"></canvas>

        <div class="hero-content">
            <div class="hero-eyebrow">Kecerdasan Buatan untuk Petani Indonesia</div>
            <h1 class="hero-title">
                Analisis Lahan Cerdas<br/>
                dengan <span class="gradient-text">AI Presisi</span>
            </h1>
            <!-- Didukung model K-Means dan MLP Neural Network yang telah diuji secara ilmiah. -->
            </p>
            <div class="hero-buttons">
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-hero-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Mulai Analisis Gratis
                </a>
                <a href="#cara-pakai" class="btn-hero-ghost">Lihat Cara Kerjanya</a>
            </div>
        </div>

        <div class="hero-footer">
            <div class="scroll-arrow">
                <svg width="20" height="20" fill="none" stroke="rgba(255,255,255,0.3)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </section>

    <!-- ─── DESKRIPSI SISTEM (white section) ─── -->
    <section id="tentang" class="section-white">
        <div class="section-inner two-col">
            <div>
                <div class="section-label" style="color:#1a73e8">Deskripsi Sistem</div>
                <h2 class="section-title" style="color:#111">Analisis tanah seakurat laboratorium, dari genggaman tangan Anda</h2>
                <p class="section-sub">
                    VESTNOOK menggabungkan data sensor IoT portabel, kondisi cuaca, 
                    dan riwayat panen untuk menghasilkan rekomendasi pupuk yang presisi dan personal 
                    bagi setiap petani.
                </p>
                <a href="{{ route('register') }}" class="learn-more">
                    Pelajari lebih lanjut
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="visual-box" style="background: none; overflow: visible; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/maingambar.png') }}" alt="Dashboard VESTNOOK" style="width: 100%; height: auto; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            </div>
        </div>
    </section>

    <!-- ─── FITUR (dark section) ─── -->
    <section id="fitur" class="section-dark">
        <div class="section-inner">
            <div class="section-label">Fitur Unggulan</div>
            <h2 class="section-title">Semua yang Anda butuhkan, dalam satu platform</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/></svg>
                    </div>
                    <h3>Scan QR Sensor IoT</h3>
                    <p>Pindai kode QR dari alat sensor portabel. Data suhu, pH, dan kadar NPK langsung terisi otomatis tanpa ketik manual.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3>AI K-Means Clustering</h3>
                    <p>Model unsupervised mengelompokkan lahan Anda ke dalam zonasi karakteristik yang tepat berdasarkan data lingkungan nyata.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3>Random Forest Classifier</h3>
                    <p>Model supervised Random Forest merekomendasikan jenis pupuk paling optimal berdasarkan 19 parameter input.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3>Insight AI Vestnook</h3>
                    <p>AI Vestnook memberikan analisis mendalam dan panduan perawatan lahan dalam bahasa yang mudah dipahami petani.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3>Riwayat Analisis</h3>
                    <p>Setiap analisis tersimpan rapi di dashboard Anda. Pantau perkembangan kesuburan lahan dari waktu ke waktu dengan mudah.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>Ramah Pengguna di HP</h3>
                    <p>Dirancang mobile-first. Seluruh fitur berjalan lancar di layar smartphone tanpa aplikasi tambahan yang perlu diunduh.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── CARA PAKAI (white section) ─── -->
    <section id="cara-pakai" class="section-white">
        <div class="section-inner two-col">
            <div>
                <div class="section-label" style="color:#1a73e8">Cara Pakai</div>
                <h2 class="section-title" style="color:#111">Tiga langkah menuju lahan yang lebih subur</h2>
                <p class="section-sub">Tidak perlu latar belakang teknologi. Dirancang semudah percakapan biasa.</p>
            </div>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-body">
                        <h3 style="color:#111">Scan QR atau Input Manual</h3>
                        <p>Pindai kode QR dari perangkat sensor IoT yang terpasang di lahan Anda, atau masukkan 19 data parameter tanah dan cuaca secara manual ke dalam form yang disediakan.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-body">
                        <h3 style="color:#111">AI Memproses Data</h3>
                        <p>Sistem akan langsung menjalankan model K-Means untuk memetakan zona lahan dan model MLP untuk mengklasifikasikan pupuk optimal berdasarkan data yang Anda berikan.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-body">
                        <h3 style="color:#111">Dapatkan Rekomendasi Lengkap</h3>
                        <p>Halaman hasil menampilkan zona lahan, jenis pupuk utama, dan panduan perawatan dari AI Vestnook yang detail dan mudah diikuti untuk lahan Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── STATISTIK (dark) ─── -->
    <section class="section-dark" style="text-align:center">
        <div class="section-inner">
            <div class="section-label" style="text-align:center">Terpercaya & Terukur</div>
            <h2 class="section-title" style="max-width:500px;margin:0 auto 16px">Angka yang berbicara sendiri</h2>
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-num">19</div>
                    <div class="stat-label">Parameter analisis tanah dan cuaca</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">4</div>
                    <div class="stat-label">Zona klasifikasi lahan K-Means</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">&lt;5s</div>
                    <div class="stat-label">Waktu proses analisis AI</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Gratis untuk petani Indonesia</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── SECTION: IoT SENSOR ─── -->
    <section id="iot" style="background:#080e1c; padding:96px 24px; color:#fff; overflow:hidden; position:relative;">

        <!-- Background glow decoration -->
        <div style="position:absolute;top:-120px;right:-120px;width:500px;height:500px;background:radial-gradient(circle,rgba(66,133,244,0.12) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-80px;left:-60px;width:400px;height:400px;background:radial-gradient(circle,rgba(52,168,83,0.08) 0%,transparent 70%);pointer-events:none;"></div>

        <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">

            <!-- Header -->
            <div style="text-align:center; margin-bottom:64px;">
                <div style="display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8ab4f8;background:rgba(66,133,244,0.12);border:1px solid rgba(66,133,244,0.25);padding:6px 16px;border-radius:100px;margin-bottom:20px;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2M2 12h2m16 0h2m-3.05-6.95l-1.41 1.41M6.46 17.54l-1.41 1.41M17.54 17.54l1.41-1.41M6.46 6.46L5.05 5.05"/></svg>
                    Teknologi Sensor
                </div>
                <h2 style="font-size:clamp(26px,4vw,44px);font-weight:700;color:#fff;margin-bottom:16px;line-height:1.2;">VESTNOOK Sensor Node</h2>
                <p style="font-size:17px;color:rgba(255,255,255,0.55);max-width:580px;margin:0 auto;line-height:1.8;">Perangkat IoT resmi yang terintegrasi langsung dengan platform VESTNOOK — cukup colok ke tanah, scan QR, dan data lahan Anda langsung terisi otomatis.</p>
            </div>

            <!-- Hero Visual + Product Card -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;margin-bottom:80px;">

                <!-- Product image -->
                <div style="position:relative;">
                    <div style="border-radius:24px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);box-shadow:0 32px 80px rgba(0,0,0,0.5);aspect-ratio:4/3;background:#111827;">
                        <img src="{{ asset('images/iot-device.png') }}" alt="VESTNOOK Sensor Node v1"
                            style="width:100%;height:100%;object-fit:cover;display:block;">
                        <!-- Placeholder overlay text for user to replace -->
                        <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(to top,rgba(0,0,0,0.7) 0%,transparent 100%);padding:24px 20px 20px;">
                            <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#8ab4f8;margin-bottom:4px;">Vestnook Sensor Node</div>
                        </div>
                    </div>
                    <!-- Badge -->
                    <div style="position:absolute;top:-16px;right:-16px;background:linear-gradient(135deg,#4285f4,#34a853);border-radius:16px;padding:10px 18px;font-size:13px;font-weight:700;color:#fff;box-shadow:0 8px 24px rgba(66,133,244,0.4);">
                        Sensor Node v1
                    </div>
                </div>

                <!-- Specs & Description -->
                <div>
                    <div style="font-size:13px;font-weight:700;color:#4285f4;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px;">Spesifikasi Perangkat</div>
                    <h3 style="font-size:28px;font-weight:700;color:#fff;margin-bottom:20px;line-height:1.3;">Sensor multi-parameter<br>dalam satu perangkat kecil</h3>
                    <p style="font-size:15px;color:rgba(255,255,255,0.6);line-height:1.8;margin-bottom:28px;">Dirancang khusus untuk kondisi lapangan Indonesia — tahan air, tahan debu, dan bertenaga surya agar bisa bekerja seharian penuh di sawah tanpa perlu charger.</p>

                    <!-- Spec grid -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Mengukur</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">pH, NPK, Suhu, Kelembapan</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Konektivitas</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">QR Code + Wi-Fi</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Daya</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">Panel Surya + Baterai</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Ketahanan</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">IP67 – Tahan Air & Debu</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Akurasi</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">±0.1 pH / ±2% RH</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:14px 16px;">
                            <div style="font-size:11px;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Kompatibel</div>
                            <div style="font-size:14px;font-weight:600;color:#fff;">Semua Jenis Tanah</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cara Kerja -->
            <div style="margin-bottom:64px;">
                <div style="text-align:center;margin-bottom:40px;">
                    <div style="font-size:13px;font-weight:700;color:#8ab4f8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">Cara Kerja</div>
                    <h3 style="font-size:clamp(22px,3vw,32px);font-weight:700;color:#fff;">Dari Tanah ke Rekomendasi dalam 3 Langkah</h3>
                </div>

                <!-- Steps with connector line -->
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;position:relative;">
                    <!-- Connector line -->
                    <div style="position:absolute;top:36px;left:calc(16.67% + 20px);right:calc(16.67% + 20px);height:2px;background:linear-gradient(to right,#4285f4,#34a853,#fbbc04);border-radius:2px;z-index:0;"></div>

                    <!-- Step 1 -->
                    <div style="text-align:center;position:relative;z-index:1;">
                        <div style="width:72px;height:72px;background:linear-gradient(135deg,#4285f4,#1a56db);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 0 0 8px rgba(66,133,244,0.12);">
                            <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h4 style="font-size:16px;font-weight:700;color:#fff;margin-bottom:10px;">1. Tancapkan Sensor</h4>
                        <p style="font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;">Tancapkan probe sensor ke tanah lahan Anda. Sensor langsung membaca pH, nitrogen, fosfor, kalium, suhu, dan kelembapan tanah secara real-time.</p>
                    </div>

                    <!-- Step 2 -->
                    <div style="text-align:center;position:relative;z-index:1;">
                        <div style="width:72px;height:72px;background:linear-gradient(135deg,#34a853,#137333);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 0 0 8px rgba(52,168,83,0.12);">
                            <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h4 style="font-size:16px;font-weight:700;color:#fff;margin-bottom:10px;">2. Scan QR Code</h4>
                        <p style="font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;">Layar LCD sensor menampilkan QR Code. Buka VESTNOOK di HP Anda, tekan "Scan Sensor IoT", arahkan kamera — semua data tanah langsung terisi otomatis ke formulir.</p>
                    </div>

                    <!-- Step 3 -->
                    <div style="text-align:center;position:relative;z-index:1;">
                        <div style="width:72px;height:72px;background:linear-gradient(135deg,#fbbc04,#e37400);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;box-shadow:0 0 0 8px rgba(251,188,4,0.12);">
                            <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h4 style="font-size:16px;font-weight:700;color:#fff;margin-bottom:10px;">3. Terima Rekomendasi AI</h4>
                        <p style="font-size:14px;color:rgba(255,255,255,0.5);line-height:1.7;">AI VESTNOOK memproses data sensor dan memberikan rekomendasi pupuk terbaik beserta analisis mendalam kondisi lahan Anda dalam hitungan detik.</p>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery: Sensor di Lapangan & Scan Demo -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:56px;">

                <div style="border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);aspect-ratio:16/10;position:relative;background:#111827;">
                    <img src="{{ asset('images/iot-field.png') }}" alt="Sensor di lahan pertanian"
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.6) 0%,transparent 60%);"></div>
                    <div style="position:absolute;bottom:20px;left:20px;">
                        <div style="font-size:12px;font-weight:600;color:#8ab4f8;letter-spacing:.05em;text-transform:uppercase;margin-bottom:4px;">Pemasangan di Lahan</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;">Sensor langsung di sawah, bekerja otomatis</div>
                    </div>
                    <!-- Replace hint -->
                    <div style="position:absolute;top:16px;right:16px;background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.15);border-radius:8px;padding:6px 10px;font-size:11px;color:rgba(255,255,255,0.5);">Vestnook Sensor Node</div>
                </div>

                <div style="border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);aspect-ratio:16/10;position:relative;background:#111827;">
                    <img src="{{ asset('images/iot-scan.png') }}" alt="Scan QR sensor IoT"
                        style="width:100%;height:100%;object-fit:cover;display:block;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.6) 0%,transparent 60%);"></div>
                    <div style="position:absolute;bottom:20px;left:20px;">
                        <div style="font-size:12px;font-weight:600;color:#8ab4f8;letter-spacing:.05em;text-transform:uppercase;margin-bottom:4px;">Scan & Otomatis Terisi</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;">QR Code mengisi data formulir secara instan</div>
                    </div>
                    <!-- Replace hint -->
                    <div style="position:absolute;top:16px;right:16px;background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.15);border-radius:8px;padding:6px 10px;font-size:11px;color:rgba(255,255,255,0.5);">Vestnook QR Generator</div>
                </div>
            </div>

            <!-- Parameter yang Diukur -->
            <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:36px 40px;">
                <div style="text-align:center;margin-bottom:32px;">
                    <h3 style="font-size:20px;font-weight:700;color:#fff;margin-bottom:6px;">Parameter yang Diukur Sensor</h3>
                    <p style="font-size:14px;color:rgba(255,255,255,0.4);">Semua data ini otomatis terisi di formulir analisis VESTNOOK</p>
                </div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;">
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">🧪</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">pH Tanah</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Keasaman & kebasaan</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">💧</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Kelembapan</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Kadar air tanah (%)</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">🌿</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Nitrogen (N)</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Kandungan nitrogen</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">⚗️</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Fosfor (P)</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Kandungan fosfor</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">🔋</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Kalium (K)</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Kandungan kalium</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">🌡️</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Suhu</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Suhu udara (°C)</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">🌤️</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Kelembapan Udara</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Humidity (%)</div>
                    </div>
                    <div style="text-align:center;padding:20px 12px;">
                        <div style="font-size:32px;margin-bottom:8px;">⚡</div>
                        <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:4px;">Konduktivitas</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.4);">Electrical conductivity</div>
                    </div>
                </div>
            </div>

            <!-- CTA Note -->
            <div style="text-align:center;margin-top:48px;">
                <div style="display:inline-flex;align-items:center;gap:12px;background:rgba(66,133,244,0.1);border:1px solid rgba(66,133,244,0.25);border-radius:16px;padding:16px 28px;">
                    <svg width="20" height="20" fill="none" stroke="#8ab4f8" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:14px;color:rgba(255,255,255,0.7);">Belum punya sensor? Tidak masalah — semua data bisa diisi manual juga di halaman analisis.</span>
                </div>
            </div>

        </div>
    </section>

    <!-- ─── SECTION: PANDUAN / TUTORIAL ─── -->
    <section id="panduan" style="background:#f8faff; padding: 80px 0;">
        <div style="max-width:1100px; margin:0 auto; padding:0 24px;">

            <!-- Header -->
            <div style="text-align:center; margin-bottom:56px;">
                <div style="display:inline-block; font-size:12px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#4285f4; background:#e8f0fe; padding:6px 16px; border-radius:100px; margin-bottom:14px;">Panduan Penggunaan</div>
                <h2 style="font-size:clamp(26px,4vw,40px); font-weight:700; color:#1c1e21; margin-bottom:12px; line-height:1.25;">Cara Pakai VESTNOOK</h2>
                <p style="font-size:16px; color:#5f6368; max-width:560px; margin:0 auto; line-height:1.7;">Ikuti 3 langkah mudah berikut untuk mendapatkan rekomendasi pupuk terbaik untuk lahan Anda.</p>
            </div>

            <!-- Steps -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:24px; margin-bottom:64px;">

                <div style="background:#fff; border-radius:16px; padding:28px; box-shadow:0 2px 12px rgba(0,0,0,0.06); position:relative; overflow:hidden;">
                    <div style="width:40px; height:40px; background:#4285f4; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <span style="color:#fff; font-size:18px; font-weight:800;">1</span>
                    </div>
                    <div style="position:absolute; top:16px; right:16px; font-size:48px; font-weight:800; color:#f0f4ff; line-height:1;">1</div>
                    <h3 style="font-size:17px; font-weight:700; color:#1c1e21; margin-bottom:8px;">Daftar &amp; Login</h3>
                    <p style="font-size:14px; color:#5f6368; line-height:1.7;">Buat akun gratis dengan nama dan email Anda. Login dan Anda langsung masuk ke dashboard petani VESTNOOK.</p>
                </div>

                <div style="background:#fff; border-radius:16px; padding:28px; box-shadow:0 2px 12px rgba(0,0,0,0.06); position:relative; overflow:hidden;">
                    <div style="width:40px; height:40px; background:#34a853; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <span style="color:#fff; font-size:18px; font-weight:800;">2</span>
                    </div>
                    <div style="position:absolute; top:16px; right:16px; font-size:48px; font-weight:800; color:#f0faf4; line-height:1;">2</div>
                    <h3 style="font-size:17px; font-weight:700; color:#1c1e21; margin-bottom:8px;">Isi Data Lahan</h3>
                    <p style="font-size:14px; color:#5f6368; line-height:1.7;">Tekan "Analisis Baru". Isi data kondisi tanah (pH, kelembapan), cuaca, dan informasi tanaman Anda. Bisa scan otomatis dari sensor IoT atau input manual.</p>
                </div>

                <div style="background:#fff; border-radius:16px; padding:28px; box-shadow:0 2px 12px rgba(0,0,0,0.06); position:relative; overflow:hidden;">
                    <div style="width:40px; height:40px; background:#fbbc04; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <span style="color:#fff; font-size:18px; font-weight:800;">3</span>
                    </div>
                    <div style="position:absolute; top:16px; right:16px; font-size:48px; font-weight:800; color:#fffbf0; line-height:1;">3</div>
                    <h3 style="font-size:17px; font-weight:700; color:#1c1e21; margin-bottom:8px;">Lihat Rekomendasi AI</h3>
                    <p style="font-size:14px; color:#5f6368; line-height:1.7;">AI kami memproses data dan memberikan rekomendasi pupuk terbaik beserta insight mendalam dari AI Vestnook tentang cara perawatan lahan Anda.</p>
                </div>


            </div>

            <!-- Panduan Input Data -->
            <div style="background:#fff; border-radius:20px; padding:40px; box-shadow:0 2px 16px rgba(0,0,0,0.07);">
                <h3 style="font-size:22px; font-weight:700; color:#1c1e21; margin-bottom:6px;">Panduan Mengisi Data Lahan</h3>
                <p style="font-size:14px; color:#8a8d91; margin-bottom:28px;">Referensi nilai yang perlu Anda siapkan sebelum analisis</p>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px;">

                    <div style="background:#f8faff; border-radius:12px; padding:20px;">
                        <div style="font-size:13px; font-weight:700; color:#4285f4; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px;">Data Tanah</div>
                        <table style="width:100%; font-size:13px; border-collapse:collapse;">
                            <tr style="border-bottom:1px solid #e8f0fe;">
                                <td style="padding:7px 0; color:#5f6368; width:55%;">pH Tanah</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">4.0 – 8.5 (ideal: 5.5–7.0)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e8f0fe;">
                                <td style="padding:7px 0; color:#5f6368;">Kelembapan Tanah</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 100 (%)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e8f0fe;">
                                <td style="padding:7px 0; color:#5f6368;">Karbon Organik</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0.5 – 5.0 (%)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e8f0fe;">
                                <td style="padding:7px 0; color:#5f6368;">Nitrogen (N)</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 200 (ppm)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #e8f0fe;">
                                <td style="padding:7px 0; color:#5f6368;">Fosfor (P)</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 200 (ppm)</td>
                            </tr>
                            <tr>
                                <td style="padding:7px 0; color:#5f6368;">Kalium (K)</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 500 (ppm)</td>
                            </tr>
                        </table>
                    </div>

                    <div style="background:#f0faf4; border-radius:12px; padding:20px;">
                        <div style="font-size:13px; font-weight:700; color:#34a853; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px;">Cuaca &amp; Lokasi</div>
                        <table style="width:100%; font-size:13px; border-collapse:collapse;">
                            <tr style="border-bottom:1px solid #d4edda;">
                                <td style="padding:7px 0; color:#5f6368; width:55%;">Suhu Udara</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">10 – 45 (°C)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #d4edda;">
                                <td style="padding:7px 0; color:#5f6368;">Kelembapan Udara</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">20 – 100 (%)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #d4edda;">
                                <td style="padding:7px 0; color:#5f6368;">Curah Hujan</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 3000 (mm/tahun)</td>
                            </tr>
                            <tr>
                                <td style="padding:7px 0; color:#5f6368;">Region / Arah Lokasi</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">Utara / Selatan / Timur / Barat / Tengah</td>
                            </tr>
                        </table>
                    </div>

                    <div style="background:#fffbf0; border-radius:12px; padding:20px;">
                        <div style="font-size:13px; font-weight:700; color:#f9ab00; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px;">Pertanian</div>
                        <table style="width:100%; font-size:13px; border-collapse:collapse;">
                            <tr style="border-bottom:1px solid #ffefc0;">
                                <td style="padding:7px 0; color:#5f6368; width:55%;">Musim Hujan</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">Apr – Sep (Kharif)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #ffefc0;">
                                <td style="padding:7px 0; color:#5f6368;">Musim Kemarau</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">Okt – Mar (Rabi)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #ffefc0;">
                                <td style="padding:7px 0; color:#5f6368;">Pancaroba</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">Peralihan (Zaid)</td>
                            </tr>
                            <tr style="border-bottom:1px solid #ffefc0;">
                                <td style="padding:7px 0; color:#5f6368;">Pupuk Musim Lalu</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 500 (kg/ha)</td>
                            </tr>
                            <tr>
                                <td style="padding:7px 0; color:#5f6368;">Hasil Panen Lalu</td>
                                <td style="padding:7px 0; color:#1c1e21; font-weight:600;">0 – 20 (ton/ha)</td>
                            </tr>
                        </table>
                    </div>

                </div>

                <!-- Tips -->
                <div style="margin-top:24px; background:#e8f0fe; border-radius:12px; padding:18px 20px; display:flex; gap:12px; align-items:flex-start;">
                    <svg width="20" height="20" fill="none" stroke="#4285f4" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div style="font-size:13px; color:#1a56db; line-height:1.7;">
                        <strong>Tips:</strong> Jika Anda tidak memiliki alat ukur tanah, perkiraan kasar sudah cukup. Contoh: Tanah sawah normal di Indonesia biasanya pH 5.5–6.5, kelembapan 40–70%, suhu 25–35°C, dan curah hujan 1500–2500 mm/tahun.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── CTA SECTION ─── -->
    <section style="background:linear-gradient(135deg,#1a1c2e 0%,#2d3561 100%); padding:80px 24px; text-align:center; color:#fff;">
        <h2 style="font-size:clamp(24px,4vw,38px); font-weight:700; margin-bottom:14px; line-height:1.3;">Siap memulai?<br/>Analisis lahan Anda sekarang.</h2>
        <p style="font-size:16px; color:rgba(255,255,255,0.7); margin-bottom:32px;">Daftar gratis dan dapatkan rekomendasi pupuk pertama Anda dalam hitungan menit.</p>
        <a href="{{ route('register') }}" class="btn-hero-primary" style="display:inline-flex;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Daftar Gratis Sekarang
        </a>
    </section>

    <!-- ─── FOOTER ─── -->
    <footer>
        <div style="font-size:15px;font-weight:500;color:rgba(255,255,255,0.5)">VESTNOOK</div>
        <div>Dibangun dengan Laravel, Python ML Engine, dan AI Vestnook API</div>
        <div style="opacity:0.5">2024 VESTNOOK. Semua hak cipta dilindungi.</div>
    </footer>

    <!-- ─── PARTICLE SCRIPT ─── -->
    <script>
    (() => {
        const canvas = document.getElementById('particle-canvas');
        const ctx = canvas.getContext('2d');

        let W, H, cx, cy, maxR;
        let particles = [];
        let mouseX = null, mouseY = null;
        
        let globalAngle = 0;
        let hueShift = 120; // Start with green hue
        let currentShape = 0;
        let timeSinceLastShape = 0;

        function resize() {
            W = canvas.width  = canvas.offsetWidth;
            H = canvas.height = canvas.offsetHeight;
            cx = W / 2;
            cy = H / 2;
            maxR = Math.min(W, H) * 0.45;
        }
        resize();
        window.addEventListener('resize', () => { resize(); initParticles(); });

        canvas.addEventListener('mousemove', e => {
            const r = canvas.getBoundingClientRect();
            mouseX = e.clientX - r.left;
            mouseY = e.clientY - r.top;
        });
        canvas.addEventListener('mouseleave', () => { mouseX = null; mouseY = null; });

        function initParticles() {
            particles = [];
            const N = 3500;
            for (let i = 0; i < N; i++) {
                // Intrinsic random properties for shape formation
                const angle = Math.random() * Math.PI * 2;
                const rFactor = Math.sqrt(Math.random()); // Even distribution in circle
                
                particles.push({
                    angle,
                    rFactor,
                    // Actual position
                    x: cx, y: cy,
                    // Base shape position (morphs over time)
                    bx: 0, by: 0,
                    size: Math.random() * 1.5 + 0.8,
                    ease: 0.04 + Math.random() * 0.04,
                    // Random jitter for pointillism look
                    jitterX: (Math.random() - 0.5) * 15,
                    jitterY: (Math.random() - 0.5) * 15,
                    alpha: 0.3 + Math.random() * 0.7
                });
            }
        }

        // Get target (x, y) based on current shape index
        function getShapeTarget(p, shapeIndex) {
            let r, tx, ty;
            const a = p.angle;
            
            switch (shapeIndex) {
                case 0: // Bunga (5 Petals)
                    r = maxR * Math.abs(Math.cos(5 / 2 * a));
                    r = r * p.rFactor;
                    break;
                case 1: // Spiral Galaxy / Pusaran
                    r = maxR * p.rFactor;
                    const spiralA = a + r * 0.005;
                    tx = r * Math.cos(spiralA);
                    ty = r * Math.sin(spiralA);
                    return { x: tx, y: ty };
                case 2: // Daun (Leaf shape pointing right)
                    // r = a * (1 + sin(t)) * (1 - 0.9 * |cos(t)|)
                    r = maxR * 0.6 * (1 + Math.sin(a)) * (1 - 0.8 * Math.abs(Math.cos(a)));
                    r = r * p.rFactor;
                    break;
                case 3: // Cincin / Matahari
                    r = maxR * (0.6 + 0.4 * p.rFactor);
                    break;
                default:
                    r = maxR * p.rFactor;
            }
            
            tx = r * Math.cos(a);
            ty = r * Math.sin(a);
            return { x: tx, y: ty };
        }

        let lastTime = 0;
        function animate(ts) {
            const dt = Math.min((ts - lastTime) / 1000, 0.05);
            lastTime = ts;

            // Update timers and globals
            globalAngle += dt * 0.15; // Slow rotation
            hueShift = (hueShift + dt * 15) % 360; // Single global hue moving slowly
            
            timeSinceLastShape += dt;
            if (timeSinceLastShape > 7) { // Ganti wujud setiap 7 detik
                currentShape = (currentShape + 1) % 4;
                timeSinceLastShape = 0;
            }

            // Clear screen
            ctx.fillStyle = 'rgba(0,0,0,0.15)'; // Slightly longer trail for smooth motion
            ctx.fillRect(0, 0, W, H);

            const hasMouse = mouseX !== null && mouseY !== null;
            const MOUSE_RADIUS = 150;
            
            const cosA = Math.cos(globalAngle);
            const sinA = Math.sin(globalAngle);

            for (let i = 0, len = particles.length; i < len; i++) {
                const p = particles[i];

                // 1. Hitung target posisi base sesuai wujud saat ini
                const targetBase = getShapeTarget(p, currentShape);
                
                // Tambahkan jitter agar bertekstur seperti lukisan pointillism
                targetBase.x += p.jitterX;
                targetBase.y += p.jitterY;

                // 2. Morphing perlahan (base bergerak ke target)
                p.bx += (targetBase.x - p.bx) * 0.03;
                p.by += (targetBase.y - p.by) * 0.03;

                // 3. Rotasikan formasi keseluruhan
                const rotX = cosA * p.bx - sinA * p.by;
                const rotY = sinA * p.bx + cosA * p.by;

                let finalTx = cx + rotX;
                let finalTy = cy + rotY;

                // 4. Interaksi Mouse (Gentle Local Magnet)
                if (hasMouse) {
                    const dx = mouseX - finalTx;
                    const dy = mouseY - finalTy;
                    const dist = Math.sqrt(dx*dx + dy*dy);
                    
                    if (dist < MOUSE_RADIUS) {
                        // Tarikan halus ke arah kursor
                        const force = (1 - dist / MOUSE_RADIUS);
                        const pull = force * 0.2; // Ga nempel banget, cuma ketarik dikit
                        finalTx += dx * pull;
                        finalTy += dy * pull;
                    }
                }

                // 5. Ease posisi asli partikel ke posisi target final
                p.x += (finalTx - p.x) * p.ease;
                p.y += (finalTy - p.y) * p.ease;

                // Gambar partikel (Warna seragam, gradasi lightness sedikit saja)
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = `hsla(${hueShift}, 85%, ${50 + p.rFactor * 20}%, ${p.alpha})`;
                ctx.fill();
            }

            requestAnimationFrame(animate);
        }

        initParticles();
        requestAnimationFrame(animate);

        // Navbar scroll
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 30);
        });
    })();
    </script>
</body>
</html>
