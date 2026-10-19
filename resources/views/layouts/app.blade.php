<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c9bc95">
    <title>{{ config('app.name', 'Buscaminas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Saira+Stencil+One&family=Special+Elite&display=swap" rel="stylesheet">

    <!-- Scripts y estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retro-theme.css') }}">
    <script src="{{ asset('js/tema.js') }}"></script>

    @stack('styles')
</head>
<body class="retro-body">
    <div class="min-h-screen flex flex-col" style="position:relative;z-index:2">

        <!-- Navegación -->
        @include('layouts.navigation')

        <!-- Contenido principal -->
        <main class="flex-1" style="padding:clamp(12px,3vw,32px)">
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')
</body>
</html>