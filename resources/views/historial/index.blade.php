<x-app-layout>
    <div class="retro-max-w-5xl retro-mx-auto">

        <!-- Récord personal -->
        <div class="retro-sheet retro-mb-6">
            <p class="retro-file-line"><b>EXPEDIENTE PERSONAL</b> · RÉCORD INDIVIDUAL</p>
            <h1 class="retro-h1 retro-mt-2">MI RÉCORD<i class="dot">.</i></h1>
            <div class="retro-rule dbl retro-mt-4 retro-mb-4"></div>
            <div id="record-personal" class="retro-text-center retro-opacity-70 retro-file-line">
                Cargando...
            </div>
        </div>

        <!-- Historial -->
        <div class="retro-sheet">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px">
                <div>
                    <p class="retro-file-line"><b>EXPEDIENTE Nº 1943-B</b> · HISTORIAL DE MISIONES</p>
                    <h1 class="retro-h1">MI HISTORIAL<i class="dot">.</i></h1>
                    <p class="retro-subline">Últimas 50 partidas registradas</p>
                </div>
                <span class="retro-official">USO OFICIAL</span>
            </div>
            <div class="retro-rule dbl retro-mt-4 retro-mb-4"></div>

            <div style="overflow-x:auto">
                <table class="retro-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estado</th>
                            <th>Tiempo</th>
                            <th>Casillas</th>
                            <th>Iniciada</th>
                            <th>Terminada</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-historial"></tbody>
                </table>
            </div>
            <p id="sin-partidas" class="retro-text-center retro-mt-6 retro-opacity-70 retro-file-line hidden">
                ▸ AÚN NO HA REALIZADO MISIONES ◂
            </p>

            <div class="retro-pageno retro-mt-6">
                <span>FORMULARIO FM-1943 · REV. C</span>
                <span>PÁG. 01 / 01</span>
            </div>
        </div>
    </div>

    <script>window.CSRF_TOKEN = '{{ csrf_token() }}';</script>
    <script src="{{ asset('js/historial.js') }}"></script>
</x-app-layout>