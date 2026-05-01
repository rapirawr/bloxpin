<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bloxpin') }} - Authentication</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0a0a0a;
            --paper: #f5f4f0;
            --accent: #ff3b00;
            --accent-soft: #ff6b40;
            --muted: #888880;
            --border: rgba(0,0,0,0.08);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        .dark {
            --glass-bg: rgba(18, 18, 18, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        .glass {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border: 1px solid var(--glass-border) !important;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            background: var(--paper);
            color: var(--ink);
            min-height: 100vh;
        }

        .dark body {
            background: var(--ink);
            color: var(--paper);
        }

        /* ── Layout ─────────────────────────────────────── */
        .auth-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .auth-wrapper {
                grid-template-columns: 520px 1fr;
            }
        }

        /* ── Form Panel ─────────────────────────────────── */
        .form-panel {
            display: flex;
            flex-direction: column;
            padding: 40px 48px;
            background: var(--paper);
            position: relative;
            z-index: 2;
        }

        .dark .form-panel {
            background: #111110;
        }

        @media (max-width: 640px) {
            .form-panel {
                padding: 28px 24px;
            }
        }

        /* Subtle grid texture on form panel */
        .form-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .dark .form-panel::before {
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        }

        /* ── Logo ───────────────────────────────────────── */
        .logo-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            width: fit-content;
        }

        .logo-mark {
            width: 40px;
            height: 40px;
            background: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .dark .logo-mark {
            background: var(--paper);
        }

        .logo-link:hover .logo-mark {
            transform: rotate(-6deg) scale(1.05);
        }

        /* Pixel accent inside logo */
        .logo-mark::after {
            content: '';
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 2px;
        }

        .logo-letter {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: var(--paper);
            line-height: 1;
        }

        .dark .logo-letter {
            color: var(--ink);
        }

        .logo-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 22px;
            letter-spacing: -0.5px;
        }

        /* ── Form Content ───────────────────────────────── */
        .form-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            padding: 48px 0;
            position: relative;
            z-index: 1;
        }

        /* ── Footer Strip ───────────────────────────────── */
        .form-footer {
            position: relative;
            z-index: 1;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        /* ── Visual Panel ───────────────────────────────── */
        .visual-panel {
            display: none;
            position: relative;
            overflow: hidden;
            background: var(--ink);
        }

        @media (min-width: 1024px) {
            .visual-panel {
                display: block;
            }
        }

        .visual-panel img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.45;
            mix-blend-mode: luminosity;
            filter: contrast(1.1);
        }

        /* Gradient overlay */
        .visual-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                #0a0a0a 0%,
                rgba(10,10,10,0.6) 50%,
                rgba(255,59,0,0.15) 100%
            );
            z-index: 1;
        }

        /* Decorative noise grain */
        .visual-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            background-size: 200px 200px;
            z-index: 2;
            pointer-events: none;
            opacity: 0.5;
        }

        /* ── Bottom Card ────────────────────────────────── */
        .visual-card {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            z-index: 3;
            padding: 32px;
            border-radius: 24px;
        }

        .card-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .card-tag::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        .card-heading {
            font-family: 'Syne', sans-serif;
            font-size: clamp(24px, 3vw, 36px);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin: 0 0 12px;
        }

        .card-heading em {
            font-style: normal;
            color: var(--accent-soft);
        }

        .card-desc {
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 24px;
            max-width: 380px;
        }

        /* Stats row */
        .card-stats {
            display: flex;
            gap: 24px;
        }

        .stat {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
        }

        .stat-label {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .stat-divider {
            width: 1px;
            background: rgba(255,255,255,0.12);
        }

        /* ── Decorative elements on visual ─────────────── */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255,59,0,0.2);
            z-index: 1;
        }

        .deco-circle-1 {
            width: 320px;
            height: 320px;
            top: -80px;
            right: -80px;
        }

        .deco-circle-2 {
            width: 200px;
            height: 200px;
            top: 60px;
            right: 60px;
        }

        .deco-grid-mark {
            position: absolute;
            top: 48px;
            left: 40px;
            z-index: 3;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .deco-line {
            height: 2px;
            background: rgba(255,255,255,0.15);
            border-radius: 2px;
        }

        /* ── Animations ─────────────────────────────────── */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-panel {
            animation: fade-up 0.5s ease both;
        }

        .visual-card {
            animation: fade-up 0.7s 0.2s ease both;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">

        <!-- ── Form Panel ─────────────────── -->
        <div class="form-panel">
            <a href="/" class="logo-link">
                <div class="logo-mark">
                    <span class="logo-letter">B</span>
                </div>
                <span class="logo-name">loxpin.</span>
            </a>

            <div class="form-content">
                {{ $slot }}
            </div>

            <p class="form-footer">
                &copy; {{ date('Y') }} Bloxpin. All rights reserved.
            </p>
        </div>

        <!-- ── Visual Panel ───────────────── -->
        <div class="visual-panel">
            <img src="{{ asset('images/auth-bg.png') }}" alt="Auth Background">

            <!-- Decorative circles -->
            <div class="deco-circle deco-circle-1"></div>
            <div class="deco-circle deco-circle-2"></div>

            <!-- Top mark -->
            <div class="deco-grid-mark">
                <div class="deco-line" style="width: 40px;"></div>
                <div class="deco-line" style="width: 24px;"></div>
            </div>

            <!-- Bottom card -->
            <div class="visual-card">
                <div class="card-tag">Bloxpin</div>

                <h2 class="card-heading">
                    Web gallery for <em>our memories</em><br>
                </h2>

                <p class="card-desc">
                    Our story is stored here forever 
                </p>

            </div>
        </div>

    </div>
</body>
</html>
