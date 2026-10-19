(function () {
    'use strict';
    const tbody = document.getElementById('tbody-historial');
    const sinPartidas = document.getElementById('sin-partidas');
    const recordEl = document.getElementById('record-personal');

    function formatTiempo(s){ if (s == null) return '—'; return s.toString().padStart(3,'0') + 's'; }
    function formatDate(d){ if (!d) return '—'; return new Date(d).toLocaleString('es-PE', { dateStyle:'short', timeStyle:'short' }); }

    function badge(estado) {
        const map = {
            victoria:   '<span class="retro-badge victoria">VICTORIA</span>',
            derrota:    '<span class="retro-badge derrota">FALLIDA</span>',
            abandonada: '<span class="retro-badge abandonada">RETIRO</span>',
            en_curso:   '<span class="retro-badge en_curso">ACTIVA</span>',
        };
        return map[estado] || estado;
    }

    async function cargarRecord() {
        try {
            const res = await fetch('/api/records/personal', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (!data.record) {
                recordEl.innerHTML = '<p class="retro-file-line">▸ AÚN NO TIENE VICTORIAS REGISTRADAS ◂</p><p class="retro-mt-2" style="font-size:13px">¡Complete su primera misión!</p>';
                return;
            }
            const r = data.record;
            recordEl.innerHTML = '<div class="retro-grid-2">'
                + '<div class="retro-stat-card"><span class="lbl">Mejor Tiempo</span><span class="val" style="color:var(--blue);font-size:36px">' + formatTiempo(r.mejor_tiempo_segundos) + '</span></div>'
                + '<div class="retro-stat-card"><span class="lbl">Total Victorias</span><span class="val" style="color:var(--green);font-size:36px">' + r.total_victorias + '</span></div>'
                + '</div>';
            recordEl.classList.remove('retro-opacity-70');
        } catch (err) {
            recordEl.innerHTML = '<p style="color:var(--red)">Error: ' + err.message + '</p>';
        }
    }

    async function cargarHistorial() {
        tbody.innerHTML = '<tr><td colspan="6" class="retro-text-center retro-opacity-70">Cargando...</td></tr>';
        try {
            const res = await fetch('/api/historial?limite=50', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            tbody.innerHTML = '';
            if (!data.partidas || data.partidas.length === 0) {
                sinPartidas.classList.remove('hidden');
                return;
            }
            sinPartidas.classList.add('hidden');
            data.partidas.forEach((p, i) => {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td class="retro-opacity-70">' + (i+1).toString().padStart(3,'0') + '</td>'
                    + '<td>' + badge(p.estado) + '</td>'
                    + '<td style="font-family:var(--mono)">' + formatTiempo(p.tiempo_segundos) + '</td>'
                    + '<td>' + p.casillas_seguras_reveladas + '/330</td>'
                    + '<td class="retro-opacity-70" style="font-size:11px">' + formatDate(p.iniciada_en) + '</td>'
                    + '<td class="retro-opacity-70" style="font-size:11px">' + formatDate(p.terminada_en) + '</td>';
                tbody.appendChild(tr);
            });
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="6" class="retro-text-center" style="color:var(--red)">Error: ' + err.message + '</td></tr>';
        }
    }

    cargarRecord();
    cargarHistorial();
})();