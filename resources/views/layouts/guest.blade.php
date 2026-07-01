<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --color-primary: #0066cc;
                --color-primary-focus: #0071e3;
                --color-primary-on-dark: #2997ff;
                --color-ink: #1d1d1f;
                --color-canvas: #ffffff;
                --color-canvas-parchment: #f5f5f7;
                --color-border: #d2d2d7;
                --color-border-focus: #0071e3;
                --color-error: #d70015;
                --color-success: #30d158;
                --radius-sm: 8px;
                --radius-md: 11px;
                --radius-lg: 18px;
                --radius-pill: 9999px;
            }

            * {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            body {
                background-color: var(--color-canvas-parchment);
                color: var(--color-ink);
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .auth-card {
                background: var(--color-canvas);
                border-radius: var(--radius-lg);
                padding: 48px 40px;
                width: 100%;
                max-width: 400px;
            }

            @media (max-width: 480px) {
                .auth-card {
                    padding: 32px 24px;
                    border-radius: 0;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
            }

            .auth-title {
                font-size: 28px;
                font-weight: 600;
                color: var(--color-ink);
                letter-spacing: -0.374px;
                margin-bottom: 8px;
            }

            .auth-subtitle {
                font-size: 15px;
                font-weight: 400;
                color: #6e6e73;
                margin-bottom: 32px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: var(--color-ink);
                margin-bottom: 6px;
                letter-spacing: -0.08px;
            }

            .form-input {
                width: 100%;
                height: 44px;
                padding: 0 16px;
                font-size: 17px;
                color: var(--color-ink);
                background: var(--color-canvas);
                border: 1px solid var(--color-border);
                border-radius: var(--radius-sm);
                outline: none;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .form-input:focus {
                border-color: var(--color-border-focus);
                box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.15);
            }

            .form-input::placeholder {
                color: #aeaeb2;
            }

            .form-error {
                font-size: 13px;
                color: var(--color-error);
                margin-top: 6px;
            }

            .form-checkbox {
                width: 18px;
                height: 18px;
                border-radius: 4px;
                border: 1px solid var(--color-border);
                cursor: pointer;
                accent-color: var(--color-primary);
            }

            .form-checkbox-label {
                font-size: 14px;
                color: #6e6e73;
                cursor: pointer;
                user-select: none;
            }

            .btn-primary {
                width: 100%;
                height: 48px;
                font-size: 15px;
                font-weight: 600;
                color: white;
                background: var(--color-primary);
                border: none;
                border-radius: var(--radius-md);
                cursor: pointer;
                transition: background 0.2s ease, transform 0.1s ease;
                letter-spacing: -0.08px;
            }

            .btn-primary:hover {
                background: #005bb5;
            }

            .btn-primary:active {
                transform: scale(0.98);
            }

            .btn-primary:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.3);
            }

            .link-forgot {
                font-size: 14px;
                color: var(--color-primary);
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .link-forgot:hover {
                color: var(--color-primary-focus);
                text-decoration: underline;
            }

            .status-message {
                font-size: 14px;
                color: var(--color-success);
                text-align: center;
                margin-bottom: 20px;
            }

            .footer-text {
                font-size: 12px;
                color: #aeaeb2;
                text-align: center;
                margin-top: 32px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center px-4">
            <div class="mb-8">
                <a href="/" class="text-2xl font-semibold tracking-tight" style="color: var(--color-ink); letter-spacing: -0.5px;">
                    {{ config('app.name', 'SDRF') }}
                </a>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>

            <p class="footer-text" style="margin-top: 40px;">&copy; {{ date('Y') }} {{ config('app.name', 'SDRF') }}. All rights reserved.</p>
        </div>
    </body>
</html>
