<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--favicon-->
        @php
            $faviconUrl = asset('images/logo/LOGO.png');
            if (auth()->check() && !auth()->user()->is_super_admin && auth()->user()->club && auth()->user()->club->logo) {
                $faviconUrl = asset(auth()->user()->club->logo);
            }
        @endphp
        <link rel="icon" href="{{ $faviconUrl . '?v=' . now()->format('H.s') }}" type="image/png">

        <title>{{ config('app.name', 'Jackeline FS') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css?v=' . (file_exists(public_path('css/app.css')) ? filemtime(public_path('css/app.css')) : '1')) }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css?v=' . (file_exists(public_path('css/bootstrap.css')) ? filemtime(public_path('css/bootstrap.css')) : '1')) }}">
        <link rel="stylesheet" href="{{ asset('css/formulario.css?v=' . (file_exists(public_path('css/formulario.css')) ? filemtime(public_path('css/formulario.css')) : '1')) }}">
        <link rel="stylesheet" href="{{ asset('css/input.css?v=' . (file_exists(public_path('css/input.css')) ? filemtime(public_path('css/input.css')) : '1')) }}">
        <link rel="stylesheet" href="{{ asset('css/modals.css?v=' . (file_exists(public_path('css/modals.css')) ? filemtime(public_path('css/modals.css')) : '1')) }}">
        <link rel="stylesheet" href="{{ asset('css/directorios.css?v=' . (file_exists(public_path('css/directorios.css')) ? filemtime(public_path('css/directorios.css')) : '1')) }}">
        <!-- icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen bg-gray-800">
            {{-- @livewire('navigation-menu') --}}

            <!-- Page Heading -->
            @if (isset($header))
            <header class="bg-gray-300 shadow">
                <div class="max-w-7xl mx-auto py-1 px-1 sm:px-1 lg:px-1">
                    {{ $header }}
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')


        <script src="{{ asset('js/app.js?v=' . (file_exists(public_path('js/app.js')) ? filemtime(public_path('js/app.js')) : '1')) }}"></script>
        <script src="{{ asset('js/formulario.js?v=' . (file_exists(public_path('js/formulario.js')) ? filemtime(public_path('js/formulario.js')) : '1')) }}"></script>
        <script src="{{ asset('js/bootstrap.js?v=' . (file_exists(public_path('js/bootstrap.js')) ? filemtime(public_path('js/bootstrap.js')) : '1')) }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.js?v=' . (file_exists(public_path('js/bootstrap.bundle.js')) ? filemtime(public_path('js/bootstrap.bundle.js')) : '1')) }}"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script> --}}
    </body>
</html>
