<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#c9bc95">
    <title>{{ config('app.name', 'Buscaminas') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Saira+Stencil+One&family=Special+Elite&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/retro-theme.css') }}">
    <script src="{{ asset('js/tema.js') }}"></script>
</head>
<body class="retro-body">
    <div class="min-h-screen flex flex-col items-center justify-center" style="padding:clamp(12px,3vw,32px);position:relative;z-index:2">

        <!-- Botón tema -->
        <div style="position:fixed;top:16px;right:16px;z-index:50">
            <button class="retro-theme-btn" title="Cambiar tema">
                <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.6v2.1M12 19.3v2.1M2.6 12h2.1M19.3 12h2.1M5.4 5.4l1.5 1.5M17.1 17.1l1.5 1.5M18.6 5.4l-1.5 1.5M6.9 17.1l-1.5 1.5"/></svg>
            </button>
        </div>

        <div class="w-full retro-max-w-md retro-mx-auto">
            <!-- Encabezado tipo expediente -->
            <div class="retro-text-center retro-mb-4">
                <p class="retro-file-line"><b>EXPEDIENTE Nº 1943-B</b> · SECCIÓN 4</p>
                <h1 class="retro-h1" style="margin-top:8px">CAMPO MINADO<i class="dot">.</i></h1>
                <p class="retro-subline">Sistema Operativo de Desminado</p>
            </div>

            {{ $slot }}
        </div>
    </div>
    @stack('scripts')
</body>
</html>