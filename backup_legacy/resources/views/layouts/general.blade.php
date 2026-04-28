<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--favicon-->
        <link rel="shortcut icon" href="{{ asset('favicon.ico?v='.now()->format('H.s')) }}" type="image/x-icon">

        <title>{{ config('app.name', 'Jackeline FS') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css?v='.now()->format('H.s')) }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css?v='.now()->format('H.s')) }}">
        <link rel="stylesheet" href="{{ asset('css/formulario.css?v='.now()->format('H.s')) }}">
        <link rel="stylesheet" href="{{ asset('css/input.css?v='.now()->format('H.s')) }}">
        <link rel="stylesheet" href="{{ asset('css/modals.css?v='.now()->format('H.s')) }}">
        <link rel="stylesheet" href="{{ asset('css/directorios.css?v='.now()->format('H.s')) }}">
        <!-- icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

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

        @livewireScripts
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/formulario.js') }}"></script>
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script> --}}
    </body>
</html>
