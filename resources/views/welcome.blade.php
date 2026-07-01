<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SDRF — Software Development Request Form</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600&display=swap" rel="stylesheet" />
    <style>
        :root {
            --black: #0a0a0a;
            --white: #fafafa;
            --blue: #0066cc;
            --blue-focus: #0077e6;
            --ink: #1d1d1f;
            --muted: #86868b;
            --muted-light: #a1a1a6;
            --surface-dark: #161616;
            --surface-card: #1c1c1e;
            --hairline: rgba(255,255,255,0.08);
            --hairline-light: rgba(0,0,0,0.06);
            --font: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font);
            color: var(--ink);
            background: var(--white);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }

        /* --- NAV --- */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            height: 48px;
            display: flex;
            align-items: center;
            padding: 0 clamp(20px, 4vw, 40px);
            background: rgba(250, 250, 250, 0.72);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid var(--hairline-light);
        }
        .nav-inner {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            position: relative;
        }
        .nav-logo {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .nav-links {
            display: flex;
            gap: 28px;
            margin-left: 40px;
        }
        .nav-links a {
            font-size: 12px;
            font-weight: 400;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: -0.01em;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--ink); }
        .nav-cta {
            position: absolute;
            right: 0;
        }
        .nav-cta a {
            display: inline-flex;
            align-items: center;
            height: 32px;
            padding: 0 14px;
            background: var(--ink);
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 8px;
            letter-spacing: -0.01em;
            transition: opacity 0.2s;
        }
        .nav-cta a:hover { opacity: 0.8; }

        /* --- HERO --- */
        .hero {
            background: var(--black);
            color: #fff;
            padding: clamp(80px, 12vh, 140px) clamp(20px, 4vw, 40px);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -40%;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0,102,204,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-eyebrow {
            font-size: 12px;
            font-weight: 500;
            color: var(--blue);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -0.035em;
            max-width: 720px;
            margin: 0 auto 24px;
        }
        .hero p {
            font-size: clamp(16px, 2vw, 19px);
            font-weight: 400;
            color: var(--muted-light);
            max-width: 520px;
            margin: 0 auto 40px;
            line-height: 1.6;
        }
        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            height: 44px;
            padding: 0 24px;
            background: var(--blue);
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            border-radius: 9999px;
            letter-spacing: -0.01em;
            transition: background 0.2s, transform 0.1s;
        }
        .btn-primary:hover { background: var(--blue-focus); }
        .btn-primary:active { transform: scale(0.97); }
        .btn-ghost {
            display: inline-flex;
            align-items: center;
            height: 44px;
            padding: 0 24px;
            background: transparent;
            color: var(--muted-light);
            font-size: 15px;
            font-weight: 400;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 9999px;
            letter-spacing: -0.01em;
            transition: color 0.2s, border-color 0.2s;
        }
        .btn-ghost:hover { color: #fff; border-color: rgba(255,255,255,0.35); }

        /* --- LIFECYCLE --- */
        .lifecycle {
            background: var(--white);
            padding: clamp(64px, 10vh, 100px) clamp(20px, 4vw, 40px);
        }
        .lifecycle-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }
        .lifecycle-header h2 {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .lifecycle-header p {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.6;
        }
        .lifecycle-track {
            display: flex;
            align-items: flex-start;
            gap: 0;
            max-width: 980px;
            margin: 0 auto;
            overflow-x: auto;
            padding-bottom: 16px;
            -webkit-overflow-scrolling: touch;
        }
        .lifecycle-step {
            flex: 1;
            min-width: 120px;
            text-align: center;
            position: relative;
            padding: 0 8px;
        }
        .lifecycle-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(50% + 22px);
            right: calc(-50% + 22px);
            height: 1px;
            background: var(--hairline-light);
        }
        .step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--ink);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }
        .lifecycle-step:last-child .step-num {
            background: var(--blue);
        }
        .step-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            letter-spacing: -0.01em;
            margin-bottom: 4px;
        }
        .step-desc {
            font-size: 11px;
            color: var(--muted);
            line-height: 1.4;
        }

        /* --- FEATURES --- */
        .features {
            background: var(--black);
            color: #fff;
            padding: clamp(64px, 10vh, 100px) clamp(20px, 4vw, 40px);
        }
        .features-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }
        .features-header h2 {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .features-header p {
            font-size: 16px;
            color: var(--muted-light);
            line-height: 1.6;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            max-width: 980px;
            margin: 0 auto;
        }
        .feature-card {
            background: var(--surface-card);
            border: 1px solid var(--hairline);
            border-radius: 16px;
            padding: 32px;
            transition: border-color 0.2s;
        }
        .feature-card:hover { border-color: rgba(255,255,255,0.15); }
        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(0,102,204,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .feature-icon svg { width: 20px; height: 20px; stroke: var(--blue); fill: none; stroke-width: 1.5; }
        .feature-card h3 {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }
        .feature-card p {
            font-size: 14px;
            color: var(--muted-light);
            line-height: 1.6;
        }

        /* --- ROLES --- */
        .roles {
            background: var(--white);
            padding: clamp(64px, 10vh, 100px) clamp(20px, 4vw, 40px);
        }
        .roles-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 64px;
        }
        .roles-header h2 {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .roles-header p {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.6;
        }
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
            max-width: 980px;
            margin: 0 auto;
        }
        .role-card {
            padding: 32px;
            border: 1px solid var(--hairline-light);
            border-radius: 16px;
            transition: border-color 0.2s;
        }
        .role-card:hover { border-color: rgba(0,0,0,0.12); }
        .role-tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            color: var(--blue);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .role-card h3 {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }
        .role-card p {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* --- CTA --- */
        .cta-section {
            background: var(--black);
            color: #fff;
            padding: clamp(64px, 10vh, 100px) clamp(20px, 4vw, 40px);
            text-align: center;
        }
        .cta-section h2 {
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 600;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .cta-section p {
            font-size: 16px;
            color: var(--muted-light);
            margin-bottom: 32px;
        }

        /* --- FOOTER --- */
        .footer {
            background: var(--white);
            padding: 32px clamp(20px, 4vw, 40px);
            text-align: center;
            border-top: 1px solid var(--hairline-light);
        }
        .footer p {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: -0.01em;
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeUp 0.6s ease-out both;
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @media (max-width: 640px) {
            .nav-links { display: none; }
            .lifecycle-track { gap: 0; }
            .lifecycle-step { min-width: 80px; }
            .lifecycle-step:not(:last-child)::after { display: none; }
            .step-desc { display: none; }
        }
    </style>
</head>
<body>

    <!-- Nav -->
    <nav class="nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">SDRF</a>
            <div class="nav-links">
                <a href="#lifecycle">Alur Kerja</a>
                <a href="#features">Fitur</a>
                <a href="#roles">Peran</a>
            </div>
            <div class="nav-cta">
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-eyebrow animate-in">Sistem Manajemen Perangkat Lunak</div>
        <h1 class="animate-in delay-1">Atur siklus permintaan software dari hulu ke hilir</h1>
        <p class="animate-in delay-2">Satu tempat untuk mengajukan, melacak, dan menyelesaikan setiap permintaan pengembangan perangkat lunak di unit TI Anda.</p>
        <div class="hero-actions animate-in delay-3">
            <a href="{{ url('/dashboard') }}" class="btn-primary">Mulai Bekerja</a>
            <a href="#lifecycle" class="btn-ghost">Lihat Alur Kerja</a>
        </div>
    </section>

    <!-- Lifecycle -->
    <section id="lifecycle" class="lifecycle">
        <div class="lifecycle-header">
            <h2>Alur Permintaan</h2>
            <p>Setiap permintaan melalui tujuh tahap terstruktur — dari pengajuan hingga penutupan.</p>
        </div>
        <div class="lifecycle-track">
            <div class="lifecycle-step animate-in delay-1">
                <div class="step-num">01</div>
                <div class="step-label">Diajukan</div>
                <div class="step-desc">User mengisi formulir</div>
            </div>
            <div class="lifecycle-step animate-in delay-1">
                <div class="step-num">02</div>
                <div class="step-label">Analisa</div>
                <div class="step-desc">Dijadwalkan untuk ditinjau</div>
            </div>
            <div class="lifecycle-step animate-in delay-2">
                <div class="step-num">03</div>
                <div class="step-label">Disetujui</div>
                <div class="step-desc">Kepala TIK menyetujui</div>
            </div>
            <div class="lifecycle-step animate-in delay-2">
                <div class="step-num">04</div>
                <div class="step-label">Dikembangkan</div>
                <div class="step-desc">Programmer mengerjakan</div>
            </div>
            <div class="lifecycle-step animate-in delay-3">
                <div class="step-num">05</div>
                <div class="step-label">UAT</div>
                <div class="step-desc">User menguji hasil</div>
            </div>
            <div class="lifecycle-step animate-in delay-3">
                <div class="step-num">06</div>
                <div class="step-label">Produksi</div>
                <div class="step-desc">Siap digunakan</div>
            </div>
            <div class="lifecycle-step animate-in delay-4">
                <div class="step-num">07</div>
                <div class="step-label">Selesai</div>
                <div class="step-desc">Ditutup dan dinilai</div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="features">
        <div class="features-header">
            <h2>Yang Dapat Dilakukan</h2>
            <p>Fungsi inta yang menjaga permintaan tetap terstruktur dan kapasitas tetap terukur.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                </div>
                <h3>Ticketing Terstruktur</h3>
                <p>Setiap request mendapat nomor unik REQ-YYYY-XXX. Lacak status dari pengajuan hingga penutupan tanpa yang terlewat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                </div>
                <h3>Kontrol Kapasitas</h3>
                <p>Batas 20 story points per developer per bulan. Estimasi otomatis berdasarkan ukuran T-Shirt: S, M, L, XL.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <h3>Penanganan Konflik</h3>
            <p>Ketika alokasi melebihi batas, sistem secara otomatis menangani dengan protokol suspend dan kloning sisa pekerjaan.</p>
            </div>
        </div>
    </section>

    <!-- Roles -->
    <section id="roles" class="roles">
        <div class="roles-header">
            <h2>Siapa yang Menggunakan</h2>
            <p>Tiga peran, satu alur kerja yang sama.</p>
        </div>
        <div class="roles-grid">
            <div class="role-card">
                <div class="role-tag">Pengelola</div>
                <h3>Kepala TIK</h3>
                <p>Menyetujui request, mengalokasikan jadwal ke programmer, dan menangani konflik alokasi kapasitas.</p>
            </div>
            <div class="role-card">
                <div class="role-tag">Pelaksana</div>
                <h3>Programmer</h3>
                <p>Melihat proyek yang ditugaskan, memperbarui status pengerjaan, dan submit hasil ke UAT.</p>
            </div>
            <div class="role-card">
                <div class="role-tag">Pemohon</div>
                <h3>User</h3>
                <p>Mengajukan request, melakukan review UAT, dan memberi rating terhadap hasil yang diterima.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <h2>Siap Memulai?</h2>
        <p>Masuk ke sistem dan ajukan permintaan pertama Anda.</p>
        <a href="{{ url('/dashboard') }}" class="btn-primary">Buka Dashboard</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} &middot; PHP v{{ PHP_VERSION }}</p>
    </footer>

</body>
</html>
