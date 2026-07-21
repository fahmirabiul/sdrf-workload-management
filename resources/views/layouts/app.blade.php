<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'IT Workload Management') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#fcf8ff] text-[#464555] selection:bg-primary/20 selection:text-primary">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col overflow-hidden relative">
                <!-- Top Header -->
                @include('layouts.topbar')

                <!-- Main Content Area -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50/50 p-4 sm:p-6 lg:p-8 transition-all duration-300">
                    <div class="max-w-7xl mx-auto">
                        <!-- Page Heading (Optional) -->
                        @if (isset($header))
                            <header class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6 hidden">
                                <div class="py-4 px-6 sm:px-8">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
