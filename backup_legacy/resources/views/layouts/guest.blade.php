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

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/app.css?v='.now()->format('H.s')) }}">
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body>
        <div class="font-sans text-gray-900 dark:bg-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
    <script src="{{ asset('js/app.js?v='.now()->format('H.s')) }}" defer></script>
</html>
