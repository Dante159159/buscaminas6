<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#c9bc95">
    <title>Buscaminas · Campo Minado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Saira+Stencil+One&family=Special+Elite&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/retro-theme.css') }}">
    <script src="{{ asset('js/tema.js') }}"></script>
</head>
<body class="retro-body">
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;z-index:2">
        <div class="retro-sheet retro-max-w-2xl retro-mx-auto retro-text-center">

            <p class="retro-file-line"><b>EXPEDIENTE Nº 1943-B</b> · SECCIÓN 4</p>
            <h1 class="retro-h1 retro-mt-2">CAMPO MINADO<i class="dot">.</i></h1>
            <p class="retro-subline">Sistema Operativo de Desminado · Manual FM-1943</p>

            <div class="retro-rule dbl retro-mt-4 retro-mb-4"></div>

            <p style="font-size:14px;line-height:1.7;max-width:540px;margin:0 auto">
                Bienvenido al sistema operativo de desminado. Su misión: despejar un terreno de <b style="color:var(--red)">20×20 casillas</b> con <b style="color:var(--red)">70 minas ocultas</b>. Registre su expediente para comenzar la operación.
            </p>

            <div class="retro-grid-3 retro-mt-6">
                <div class="retro-stat-card">
                    <span class="lbl">Tablero</span>
                    <span class="val">20×20</span>
                </div>
                <div class="retro-stat-card">
                    <span class="lbl">Minas</span>
                    <span class="val">70</span>
                </div>
                <div class="retro-stat-card">
                    <span class="lbl">Seguras</span>
                    <span class="val">330</span>
                </div>
            </div>

            <div class="retro-rule retro-mt-6 retro-mb-4"></div>

            <div class="retro-flex retro-gap-4 retro-justify-center" style="flex-wrap:wrap">
                <a href="{{ route('login') }}" class="retro-btn primary lg">▸ Acceder</a>
                <a href="{{ route('register') }}" class="retro-btn lg">▸ Registrarse</a>
            </div>

            <div class="retro-pageno retro-mt-6">
                <span>FORMULARIO FM-1943 · REV. C</span>
                <span>PÁG. 01 / 01</span>
            </div>
        </div>
    </div>
</body>
</html>