<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SDRF') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #f8fafc;
                background-image: radial-gradient(circle, #cbd5e1 1px, transparent 1px);
                background-size: 24px 24px;
            }

            @media (prefers-reduced-motion: reduce) {
                .transition-all {
                    transition: none !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-[#1e293b]">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2.5 mb-8">
                <x-application-logo class="h-8 w-auto text-primary" />
                <span class="text-lg font-semibold text-[#1e293b] tracking-tight">SDRF</span>
            </a>

            <!-- Card -->
            <div class="w-full max-w-md bg-white rounded-xl p-8 lg:p-10">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-sm text-[#64748b]">
                &copy; {{ date('Y') }} SDRF &mdash; University IT Department
            </p>
        </div>
    </body>
</html>
