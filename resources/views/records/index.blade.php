<x-app-layout>
    <div class="retro-max-w-5xl retro-mx-auto">
        <div class="retro-sheet">
            <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px">
                <div>
                    <p class="retro-file-line"><b>EXPEDIENTE Nº 1943-B</b> · SECCIÓN 4 · HALL DE LA FAMA</p>
                    <h1 class="retro-h1">RÉCORDS OPERATIVOS<i class="dot">.</i></h1>
                    <p class="retro-subline">Top 50 · Mejores Tiempos · 20×20 · 70 minas</p>
                </div>
                <span class="retro-official">USO OFICIAL</span>
            </div>
            <div class="retro-rule dbl retro-mt-4 retro-mb-4"></div>

            <div class="retro-flex retro-justify-between retro-items-center retro-mb-4" style="flex-wrap:wrap;gap:8px">
                <p class="retro-file-line">▸ Clasificación por menor tiempo</p>
                <button id="btn-recargar" class="retro-btn sm">▸ Actualizar</button>
            </div>

            <div style="overflow-x:auto">
                <table class="retro-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Soldado</th>
                            <th>Mejor Tiempo</th>
                            <th>Victorias</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-records"></tbody>
                </table>
            </div>
            <p id="sin-records" class="retro-text-center retro-mt-6 retro-opacity-70 retro-file-line hidden">
                ▸ AÚN NO HAY RÉCORDS REGISTRADOS ◂
            </p>

            <div class="retro-pageno retro-mt-6">
                <span>FORMULARIO FM-1943 · REV. C</span>
                <span>PÁG. 01 / 01</span>
            </div>
        </div>
    </div>

    <script>window.CSRF_TOKEN = '{{ csrf_token() }}';</script>
    <script src="{{ asset('js/records.js') }}"></script>
</x-app-layout>