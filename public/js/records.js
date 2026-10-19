(function () {
    'use strict';
    const tbody = document.getElementById('tbody-records');
    const sinRecords = document.getElementById('sin-records');
    const btn = document.getElementById('btn-recargar');

    function formatTiempo(s){ 
        if (s == null) return '—';
        return s.toString().padStart(3,'0') + 's'; 
    }

    async function cargar() {
        tbody.innerHTML = '<tr><td colspan="4" class="retro-text-center retro-opacity-70">Cargando...</td></tr>';
        sinRecords.classList.add('hidden');
        
        try {
            const res = await fetch('/api/records?limite=50', { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            });
            
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            
            const data = await res.json();
            
            // ✅ Verificar que data.ranking sea un array
            let ranking = data.ranking;
            if (!Array.isArray(ranking)) {
                ranking = [];
            }
            
            tbody.innerHTML = '';
            
            if (ranking.length === 0) {
                sinRecords.classList.remove('hidden');
                return;
            }
            
            ranking.forEach(function(r, i) {
                const tr = document.createElement('tr');
                const medalla = i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : (i+1).toString().padStart(2,'0');
                tr.innerHTML = '<td style="font-weight:700">' + medalla + '</td>'
                             + '<td>' + (r.jugador || '—') + '</td>'
                             + '<td style="color:var(--blue);font-weight:700">' + formatTiempo(r.mejor_tiempo_segundos) + '</td>'
                             + '<td style="color:var(--green);font-weight:700">' + (r.total_victorias || 0) + '</td>';
                tbody.appendChild(tr);
            });
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="4" class="retro-text-center" style="color:var(--red)">Error: ' + err.message + '</td></tr>';
        }
    }

    btn.addEventListener('click', cargar);
    cargar();
})();