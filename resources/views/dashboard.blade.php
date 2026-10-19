<x-app-layout>
    <div class="retro-max-w-5xl retro-mx-auto">

        <!-- Encabezado tipo expediente -->
        <div class="retro-sheet retro-mb-6">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px">
                <div>
                    <p class="retro-file-line"><b>EXPEDIENTE Nº 1943-B</b> · SECCIÓN 4 · DESPEJE DE TERRENO · {{ date('d M Y') }}</p>
                    <h1 class="retro-h1">BIENVENIDO, SOLDADO<i class="dot">.</i></h1>
                    <p class="retro-subline">Sistema Operativo de Desminado · Nivel Sargento</p>
                </div>
                <span class="retro-official">USO OFICIAL</span>
            </div>
            <div class="retro-rule dbl"></div>
            <p style="font-family:var(--mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-55);margin-top:10px">
                ▸ Operador: <b style="color:var(--ink)">{{ Auth::user()->name }}</b> · Identificado: {{ Auth::user()->email }}
            </p>
        </div>

        <!-- Tarjetas de acción -->
        <div class="retro-grid-3 retro-mb-6">
            <a href="/juego" class="retro-stat-card" style="text-decoration:none;color:inherit;cursor:pointer">
                <span style="font-size:32px">⚔</span>
                <span class="lbl">Iniciar Misión</span>
                <span class="val" style="font-size:14px">Comenzar partida</span>
            </a>
            <a href="/records" class="retro-stat-card" style="text-decoration:none;color:inherit;cursor:pointer">
                <span style="font-size:32px">🏆</span>
                <span class="lbl">Salón de la Fama</span>
                <span class="val" style="font-size:14px">Ver récords</span>
            </a>
            <a href="/historial" class="retro-stat-card" style="text-decoration:none;color:inherit;cursor:pointer">
                <span style="font-size:32px">📋</span>
                <span class="lbl">Expediente Personal</span>
                <span class="val" style="font-size:14px">Mi historial</span>
            </a>
        </div>

        <!-- Manual de campo -->
        <div class="retro-sheet">
            <h2 class="retro-h3 retro-mb-4">▸ Manual de Campo · Protocolo FM-1943</h2>
            <div class="retro-rule"></div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px;margin-top:14px">
                <div>
                    <p class="retro-file-line retro-mb-2"><b>01</b> Objetivo</p>
                    <p style="font-size:13px;line-height:1.6">Despejar un terreno de 20×20 casillas que contiene 70 minas ocultas, revelando las 330 casillas seguras sin detonar ninguna mina.</p>
                </div>
                <div>
                    <p class="retro-file-line retro-mb-2"><b>02</b> Controles</p>
                    <p style="font-size:13px;line-height:1.6"><b>Clic izquierdo</b>: excavar casilla. <b>Clic derecho</b>: plantar bandera. <b>Clic en número</b>: abrir vecinos si las banderas coinciden.</p>
                </div>
                <div>
                    <p class="retro-file-line retro-mb-2"><b>03</b> Niveles</p>
                    <p style="font-size:13px;line-height:1.6">Configuración fija: <b>Medio-Experto</b>. 400 casillas totales, 70 minas, 330 seguras. Primer clic siempre seguro.</p>
                </div>
                <div>
                    <p class="retro-file-line retro-mb-2"><b>04</b> Clasificación</p>
                    <p style="font-size:13px;line-height:1.6">Los récords se ordenan por menor tiempo. Solo cuentan las victorias. Las derrotas y abandonos se registran en tu expediente.</p>
                </div>
            </div>
            <div class="retro-pageno">
                <span>FORMULARIO FM-1943 · REV. C</span>
                <span>PÁG. 01 / 01</span>
            </div>
        </div>
    </div>
</x-app-layout>