<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IT Workload Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background-color: #030712;
                background-image: radial-gradient(circle at center, rgba(79, 70, 229, 0.35) 0%, rgba(30, 27, 75, 0.95) 60%, #030712 100%);
                background-size: cover;
                background-attachment: fixed;
            }

            @media (prefers-reduced-motion: reduce) {
                .transition-all {
                    transition: none !important;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-200 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
            <!-- Card -->
            <div class="w-full max-w-md bg-slate-900/60 backdrop-blur-xl border border-white/10 shadow-2xl shadow-indigo-950/50 rounded-3xl p-8 lg:p-10">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-xs text-slate-500 font-medium">
                &copy; {{ date('Y') }} IT Workload Management &mdash; University IT Department
            </p>
        </div>
    </body>
</html>
