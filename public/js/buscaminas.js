(function () {
'use strict';

const store = {
  get(k){ try { return window.localStorage.getItem(k); } catch(e){ return null; } },
  set(k,v){ try { window.localStorage.setItem(k,v); } catch(e){} }
};

const $ = function(s){ return document.querySelector(s); };
const boardEl    = $('#board'),
      scroller   = $('#scroller'),
      outer      = $('#outer'),
      fx         = $('#fx'),
      flash      = $('#flash'),
      stampLayer = $('#stampLayer'),
      mineVal    = $('#mineVal'),
      timeVal    = $('#timeVal'),
      bestVal    = $('#bestVal'),
      chip       = $('#chip'),
      gridInfo   = $('#gridInfo'),
      posInfo    = $('#posInfo'),
      modeBtn    = $('#modeBtn'),
      soundBtn   = $('#soundBtn'),
      themeBtn   = $('#themeBtn'),
      resetBtn   = $('#resetBtn'),
      todayEl    = $('#today');

const COLS = 20, ROWS = 20, TOTAL = 400, MINES_N = 70, CASILLAS_SEGURAS = 330;
const LETTERS = 'ABCDEFGHIJKLMNOPQRST';

const FLAG_SVG = '<svg viewBox="0 0 24 24"><path d="M7 21.5V3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><path d="M7 4.2 17.5 7.4 7 10.8Z" style="fill:var(--red)" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/><path d="M4.2 21.5h6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
const MINE_SVG = '<svg viewBox="0 0 24 24"><g stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 2.6v4M12 17.4v4M2.6 12h4M17.4 12h4M5.2 5.2 8 8M16 16l2.8 2.8M18.8 5.2 16 8M8 16l-2.8 2.8"/></g><circle cx="12" cy="12" r="5.6" fill="currentColor"/><circle cx="10" cy="10" r="1.5" style="fill:var(--paper)" opacity=".9"/></svg>';
const CROSS_SVG = '<svg class="cross" viewBox="0 0 24 24"><path d="M5 5 19 19" style="stroke:var(--red)" stroke-width="2.6" stroke-linecap="round"/><path d="M19 5 5 19" style="stroke:var(--red)" stroke-width="2.6" stroke-linecap="round"/></svg>';

// Estado del juego (100% cliente)
let partidaId = null;
let minasMatrix = [];      // matriz de minas del servidor
let numerosMatrix = [];    // matriz de números del servidor
let cells = [];            // estado de cada casilla (cliente)
let revealedN = 0, flaggedN = 0;
let over = false, firstClick = true, timerId = null, t0 = 0, elapsed = 0, gameId = 0;
let flagMode = false, muted = store.get('minas-mute') === '1';
let lastTickAt = 0, lpTimer = null, lpFired = false, lastTouch = 0;
let iniciadaEn = null;
let finalizando = false;

let theme = store.get('minas-theme');
if (theme !== 'day' && theme !== 'night') {
  theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'night' : 'day';
}

// ---------- Audio ----------
let actx = null;
function audio() {
  if (muted) return null;
  try {
    if (!actx) {
      const AC = window.AudioContext || window.webkitAudioContext;
      if (!AC) return null;
      actx = new AC();
    }
    if (actx.state === 'suspended') actx.resume();
    return actx;
  } catch(e){ return null; }
}
function tone(f0, f1, dur, type, vol, when) {
  const ctx = audio(); if (!ctx) return;
  if (!when) when = 0;
  try {
    const t = ctx.currentTime + when, o = ctx.createOscillator(), g = ctx.createGain();
    o.type = type; o.frequency.setValueAtTime(Math.max(1, f0), t);
    if (f1) o.frequency.exponentialRampToValueAtTime(Math.max(1, f1), t + dur);
    g.gain.setValueAtTime(vol, t);
    g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
    o.connect(g); g.connect(ctx.destination);
    o.start(t); o.stop(t + dur + 0.05);
  } catch(e){}
}
function sTick() {
  const n = performance.now();
  if (n - lastTickAt < 40) return;
  lastTickAt = n;
  tone(760 + Math.random()*260, 0, 0.045, 'square', 0.028);
}
function sFlag(on) { if (on) tone(300,170,0.08,'triangle',0.07); else tone(190,320,0.07,'triangle',0.05); }
function sBoom() {
  const ctx = audio(); if (!ctx) return;
  try {
    const dur = 0.9, n = Math.floor(ctx.sampleRate * dur);
    const buf = ctx.createBuffer(1, n, ctx.sampleRate), d = buf.getChannelData(0);
    for (let i = 0; i < n; i++) { d[i] = (Math.random()*2-1) * Math.pow(1-i/n, 1.7); }
    const src = ctx.createBufferSource(); src.buffer = buf;
    const f = ctx.createBiquadFilter(); f.type = 'lowpass';
    const t = ctx.currentTime;
    f.frequency.setValueAtTime(2400, t);
    f.frequency.exponentialRampToValueAtTime(70, t + dur);
    const g = ctx.createGain();
    g.gain.setValueAtTime(0.5, t);
    g.gain.exponentialRampToValueAtTime(0.001, t + dur);
    src.connect(f); f.connect(g); g.connect(ctx.destination);
    src.start(t);
    tone(140, 34, 0.55, 'sine', 0.45);
  } catch(e){}
}
function sWin() { const fs = [523, 659, 784, 1047]; for (let i = 0; i < fs.length; i++) tone(fs[i], 0, 0.16, 'triangle', 0.07, i*0.13); }

// ---------- Utilidades ----------
function pad3(n){ return String(Math.max(0, Math.min(999, n))).padStart(3, '0'); }
function fmtMines(n) {
  if (n < 0) return '-' + String(Math.min(99, -n)).padStart(2, '0');
  return pad3(n);
}
function later(fn, ms) {
  const gid = gameId;
  setTimeout(function(){ if (gid === gameId) fn(); }, ms);
}
function setStatus(t, cls) { chip.textContent = t; chip.className = 'chip ' + cls; }
function cssVar(name) {
  try { return getComputedStyle(document.body).getPropertyValue(name).trim(); }
  catch(e){ return '#a33327'; }
}
function getCsrfHeaders() {
  return {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.CSRF_TOKEN,
  };
}

// ---------- Tablero (solo UI) ----------
function buildBoard() {
  boardEl.innerHTML = '';
  const frag = document.createDocumentFragment();
  const corner = document.createElement('div');
  corner.className = 'ax'; frag.appendChild(corner);
  for (let c = 0; c < COLS; c++) {
    const el = document.createElement('div');
    el.className = 'ax colx';
    el.textContent = String(c+1).padStart(2, '0');
    frag.appendChild(el);
  }
  cells = [];
  for (let r = 0; r < ROWS; r++) {
    const rl = document.createElement('div');
    rl.className = 'ax rowx'; rl.textContent = LETTERS[r];
    frag.appendChild(rl);
    for (let c = 0; c < COLS; c++) {
      const b = document.createElement('button');
      b.type = 'button'; b.className = 'cell';
      b.dataset.fila = String(r); b.dataset.col = String(c);
      b.setAttribute('aria-label', 'Casilla ' + LETTERS[r] + '-' + (c+1));
      frag.appendChild(b);
      cells.push({ el: b, fila: r, col: c, revealed: false, flagged: false, count: 0 });
    }
  }
  boardEl.appendChild(frag);
}
function fitCells() {
  const avail = Math.max(160, scroller.clientWidth - 42);
  const size = Math.max(18, Math.min(32, Math.floor(avail / COLS)));
  boardEl.style.setProperty('--cell', size + 'px');
  boardEl.style.gridTemplateColumns = '26px repeat(' + COLS + ',' + size + 'px)';
}
function getCell(fila, col) {
  if (fila < 0 || fila >= ROWS || col < 0 || col >= COLS) return null;
  return cells[fila * COLS + col];
}
function updateMines() { mineVal.textContent = fmtMines(MINES_N - flaggedN); }
function updateGridNote() {
  gridInfo.textContent = 'CUADRÍCULA ' + LETTERS[0] + '–' + LETTERS[ROWS-1] + ' × 01–' + String(COLS).padStart(2,'0') + ' · ESCALA 1:100';
}

// ---------- Cronómetro ----------
function startTimer() {
  t0 = Date.now(); elapsed = 0;
  setStatus('EN CURSO', 'idle');
  timerId = setInterval(function() {
    elapsed = Math.min(999, Math.floor((Date.now() - t0) / 1000));
    timeVal.textContent = pad3(elapsed);
  }, 250);
}
function stopTimer() {
  if (timerId) { clearInterval(timerId); timerId = null; }
  if (t0) elapsed = Math.min(999, Math.floor((Date.now() - t0) / 1000));
  timeVal.textContent = pad3(elapsed);
}

// ---------- Lógica de revelado (100% cliente) ----------
function paintOpen(cell, delay) {
  later(function() {
    if (!cell || !cell.revealed) return;
    cell.el.classList.add('open');
    if (cell.count > 0) cell.el.innerHTML = '<span class="num n' + cell.count + '">' + cell.count + '</span>';
    sTick();
  }, delay);
}
function vecinos(fila, col) {
  const out = [];
  for (let df = -1; df <= 1; df++) {
    for (let dc = -1; dc <= 1; dc++) {
      if (df === 0 && dc === 0) continue;
      const n = getCell(fila + df, col + dc);
      if (n) out.push(n);
    }
  }
  return out;
}
function floodReveal(start) {
  const order = [[start, 0]], seen = new Set([start.fila * COLS + start.col]);
  let head = 0;
  while (head < order.length) {
    const cur = order[head]; head++;
    const cell = cur[0], dep = cur[1];
    if (cell.revealed || cell.flagged) continue;
    if (minasMatrix[cell.fila][cell.col] === -1) continue;
    cell.revealed = true;
    cell.count = numerosMatrix[cell.fila][cell.col];
    revealedN++;
    paintOpen(cell, Math.min(dep * 18, 640));
    if (cell.count === 0) {
      vecinos(cell.fila, cell.col).forEach(function(n) {
        const key = n.fila * COLS + n.col;
        if (!seen.has(key)) { seen.add(key); order.push([n, dep+1]); }
      });
    }
  }
  checkWin();
}
function checkWin() {
  if (!over && revealedN === CASILLAS_SEGURAS) win();
}
function tryReveal(fila, col) {
  const cell = getCell(fila, col);
  if (!cell || over || cell.flagged || cell.revealed) return;

  // Primera jugada: arrancar el timer
  if (firstClick) {
    firstClick = false;
    startTimer();
  }

  // ¿Pisó mina?
  if (minasMatrix[fila][col] === -1) {
    cell.revealed = true;
    cell.el.classList.add('open', 'mine', 'dead');
    cell.el.innerHTML = MINE_SVG;
    detonate(fila, col);
    return;
  }
  floodReveal(cell);
}
function chord(fila, col) {
  const cell = getCell(fila, col);
  if (!cell || !cell.revealed || cell.count === 0 || over) return;
  let fl = 0;
  const vecinas = vecinos(fila, col);
  vecinas.forEach(function(n){ if (n.flagged) fl++; });
  if (fl !== cell.count) return;
  vecinas.forEach(function(n) {
    if (!n.flagged && !n.revealed) {
      // Si alguna es mina, detona
      if (minasMatrix[n.fila][n.col] === -1) {
        n.revealed = true;
        n.el.classList.add('open', 'mine', 'dead');
        n.el.innerHTML = MINE_SVG;
        detonate(n.fila, n.col);
        return;
      }
      floodReveal(n);
    }
  });
}
function toggleFlag(fila, col) {
  if (over) return;
  const cell = getCell(fila, col);
  if (!cell || cell.revealed) return;
  if (cell.flagged) {
    cell.flagged = false; flaggedN--;
    cell.el.classList.remove('flagged'); cell.el.innerHTML = '';
  } else {
    cell.flagged = true; flaggedN++;
    cell.el.classList.add('flagged'); cell.el.innerHTML = FLAG_SVG;
  }
  updateMines(); sFlag(cell.flagged);
}

// ---------- Efectos ----------
function cellCenter(fila, col) {
  const cell = getCell(fila, col);
  if (!cell) return [0, 0];
  const r = cell.el.getBoundingClientRect(), o = outer.getBoundingClientRect();
  return [r.left - o.left + r.width/2, r.top - o.top + r.height/2];
}
function spawnRing(fila, col) {
  const p = cellCenter(fila, col);
  const ring = document.createElement('div');
  ring.className = 'ring';
  ring.style.left = p[0] + 'px'; ring.style.top = p[1] + 'px';
  fx.appendChild(ring);
  setTimeout(function(){ ring.remove(); }, 750);
}
function spawnShards(fila, col) {
  const p = cellCenter(fila, col), x = p[0], y = p[1];
  const cRed = cssVar('--red'), cDark = cssVar('--shard-dark');
  for (let s = 0; s < 14; s++) {
    const sh = document.createElement('div');
    sh.className = 'shard';
    sh.style.left = x + 'px'; sh.style.top = y + 'px';
    sh.style.background = Math.random() < 0.45 ? cRed : cDark;
    fx.appendChild(sh);
    const a = Math.random() * Math.PI * 2, v = 34 + Math.random() * 95;
    const dx = Math.cos(a) * v, dy = Math.sin(a) * v - 30;
    const rot = Math.floor(Math.random() * 720 - 360);
    const dur = 520 + Math.random() * 380;
    if (sh.animate) {
      sh.animate([
        { transform: 'translate(-50%,-50%) rotate(0deg)', opacity: 1 },
        { transform: 'translate(calc(-50% + ' + dx + 'px), calc(-50% + ' + dy + 'px)) rotate(' + rot + 'deg)', opacity: 0 }
      ], { duration: dur, easing: 'cubic-bezier(.15,.6,.4,1)' }).onfinish = function(){ sh.remove(); };
    } else {
      setTimeout(function(){ sh.remove(); }, dur);
    }
  }
}
function shakeBoard() {
  boardEl.classList.remove('shakeIt');
  void boardEl.offsetWidth;
  boardEl.classList.add('shakeIt');
}
function flashGo() {
  flash.classList.remove('go');
  void flash.offsetWidth;
  flash.classList.add('go');
}

// ---------- Derrota (envía a BD al final) ----------
function detonate(fila, col) {
  over = true; stopTimer(); setStatus('DETONADO', 'warn');
  spawnRing(fila, col); spawnShards(fila, col); shakeBoard(); flashGo(); sBoom();

  // Revelar todas las minas
  for (let f = 0; f < ROWS; f++) {
    for (let c = 0; c < COLS; c++) {
      const cell = getCell(f, c);
      if (f === fila && c === col) continue;
      const dist = Math.hypot(f - fila, c - col);
      if (minasMatrix[f][c] === -1 && !cell.flagged) {
        later(function() {
          cell.revealed = true;
          cell.el.classList.add('open', 'mine');
          cell.el.innerHTML = MINE_SVG;
          tone(90 + Math.random()*60, 40, 0.12, 'sawtooth', 0.04);
        }, 240 + dist * 46);
      } else if (minasMatrix[f][c] !== -1 && cell.flagged) {
        later(function() {
          cell.el.classList.add('wrong');
          cell.el.insertAdjacentHTML('beforeend', CROSS_SVG);
        }, 320 + dist * 46);
      }
    }
  }
  later(function() {
    showStamp('DETONADO', 'red', 'MISIÓN FALLIDA · EXPEDIENTE CERRADO');
  }, 780);

  // ⚡ ÚNICA PETICIÓN A BD: finalizar partida
  finalizarPartida('derrota', elapsed, revealedN);
}

// ---------- Victoria (envía a BD al final) ----------
function win() {
  over = true; stopTimer(); setStatus('DESPEJADO', 'ok');
  // Plantar banderas en minas restantes (efecto visual)
  let k = 0;
  for (let f = 0; f < ROWS; f++) {
    for (let c = 0; c < COLS; c++) {
      const cell = getCell(f, c);
      if (minasMatrix[f][c] === -1 && !cell.flagged) {
        const idx = f * COLS + c, delay = 140 + k * 25;
        k++;
        later(function() {
          cell.flagged = true;
          cell.el.classList.add('flagged');
          cell.el.innerHTML = FLAG_SVG;
        }, delay);
      }
    }
  }
  flaggedN = MINES_N;
  updateMines();
  sWin();
  later(function() {
    showStamp('DESPEJADO', 'green', 'TIEMPO ' + pad3(elapsed) + ' S');
    shakeBoard();
  }, 760);

  // ⚡ ÚNICA PETICIÓN A BD: finalizar partida
  finalizarPartida('victoria', elapsed, CASILLAS_SEGURAS);
}

// ---------- Abandonar (envía a BD) ----------
async function abandonarPartida() {
  if (!partidaId || over || finalizando) return;
  if (!confirm('¿Confirmar retirada? Se registrará como abandono.')) return;
  stopTimer();
  over = true;
  setStatus('ABANDONADA', 'warn');
  showStamp('RETIRO', 'red', 'MISIÓN ABANDONADA · ' + pad3(elapsed) + ' S');
  await finalizarPartida('abandonada', elapsed, revealedN);
}

// ---------- Finalizar partida (única petición a BD) ----------
async function finalizarPartida(estado, tiempo, casillas) {
  if (finalizando || !partidaId) return;
  finalizando = true;
  try {
    const res = await fetch('/api/partidas/' + partidaId + '/finalizar', {
      method: 'POST',
      headers: getCsrfHeaders(),
      body: JSON.stringify({
        estado: estado,
        tiempo_segundos: tiempo,
        casillas_reveladas: casillas,
      }),
    });
    const data = await res.json();
    if (data.record && data.record.mejor_tiempo_segundos !== null) {
      bestVal.textContent = pad3(data.record.mejor_tiempo_segundos);
      bestVal.classList.remove('rec-flash');
      void bestVal.offsetWidth;
      bestVal.classList.add('rec-flash');
    }
    return data;
  } catch (err) {
    console.error('Error al finalizar:', err);
  } finally {
    finalizando = false;
  }
}

// ---------- Sello ----------
function showStamp(text, color, sub) {
  stampLayer.innerHTML = '<div class="stamp ' + color + '"><span>' + text + '</span><span class="sub">' + sub + '</span></div>';
  const st = stampLayer.firstElementChild;
  requestAnimationFrame(function() {
    requestAnimationFrame(function() { st.classList.add('on'); });
  });
}

// ---------- Tema ----------
function paintTheme() {
  document.body.classList.toggle('night', theme === 'night');
  themeBtn.title = (theme === 'night') ? 'Cambiar a modo día' : 'Cambiar a modo noche';
  const meta = document.querySelector('meta[name="theme-color"]');
  if (meta) meta.setAttribute('content', theme === 'night' ? '#141009' : '#c9bc95');
}

// ---------- Nueva partida (única petición inicial a BD) ----------
async function newGame() {
  gameId++;
  if (timerId) { clearInterval(timerId); timerId = null; }
  over = false; firstClick = true; revealedN = 0; flaggedN = 0; elapsed = 0; t0 = 0;
  finalizando = false;
  stampLayer.innerHTML = ''; fx.innerHTML = '';
  timeVal.textContent = '000';
  setStatus('CARGANDO...', 'warn');
  buildBoard(); fitCells(); updateMines(); updateGridNote();
  posInfo.textContent = 'POS — —';

  try {
    const res = await fetch('/api/partidas', {
      method: 'POST',
      headers: getCsrfHeaders(),
    });
    if (!res.ok) {
      const err = await res.json();
      console.error('Error al crear partida:', err);
      setStatus('ERROR', 'warn');
      return;
    }
    const data = await res.json();
    partidaId = data.partida_id;
    minasMatrix = data.tablero.minas;
    numerosMatrix = data.tablero.numeros;
    iniciadaEn = data.iniciada_en;
    setStatus('EN ESPERA', 'idle');
  } catch (err) {
    console.error('Error de red al crear partida:', err);
    setStatus('ERROR', 'warn');
  }
}

// ---------- Cargar récord personal ----------
async function cargarRecordPersonal() {
  try {
    const res = await fetch('/api/records/personal', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    if (data.record && data.record.mejor_tiempo_segundos != null) {
      bestVal.textContent = pad3(data.record.mejor_tiempo_segundos);
    } else {
      bestVal.textContent = '—';
    }
  } catch (e) { /* silencioso */ }
}

// ---------- Eventos ----------
boardEl.addEventListener('click', function(e) {
  const b = e.target.closest('.cell');
  if (!b) return;
  audio();
  if (lpFired) { lpFired = false; return; }
  if (over) return;
  const fila = Number(b.dataset.fila), col = Number(b.dataset.col);
  const cell = getCell(fila, col);
  if (flagMode) { toggleFlag(fila, col); return; }
  if (cell.revealed) { chord(fila, col); return; }
  if (cell.flagged) return;
  tryReveal(fila, col);  // ⚡ LOCAL, sin petición a BD
});
boardEl.addEventListener('contextmenu', function(e) {
  e.preventDefault();
  if (performance.now() - lastTouch < 900) return;
  const b = e.target.closest('.cell');
  if (!b || over) return;
  toggleFlag(Number(b.dataset.fila), Number(b.dataset.col));
});
boardEl.addEventListener('touchstart', function(e) {
  lastTouch = performance.now();
  const b = e.target.closest('.cell');
  if (!b || over) return;
  lpFired = false;
  const fila = Number(b.dataset.fila), col = Number(b.dataset.col);
  lpTimer = setTimeout(function() {
    lpFired = true;
    if (!flagMode && !getCell(fila, col).revealed) toggleFlag(fila, col);
    if (navigator.vibrate) navigator.vibrate(28);
  }, 430);
}, { passive: true });
boardEl.addEventListener('touchend', function(){ clearTimeout(lpTimer); }, { passive: true });
boardEl.addEventListener('touchcancel', function(){ clearTimeout(lpTimer); }, { passive: true });
boardEl.addEventListener('touchmove', function(){ clearTimeout(lpTimer); }, { passive: true });
boardEl.addEventListener('mouseover', function(e) {
  const b = e.target.closest('.cell');
  if (!b) return;
  const fila = Number(b.dataset.fila), col = Number(b.dataset.col);
  posInfo.textContent = 'POS ' + LETTERS[fila] + '-' + String(col+1).padStart(2,'0');
});
boardEl.addEventListener('mouseleave', function(){ posInfo.textContent = 'POS — —'; });

modeBtn.addEventListener('click', function() {
  flagMode = !flagMode;
  document.body.classList.toggle('flag-mode', flagMode);
  tone(flagMode ? 520 : 420, 0, 0.05, 'square', 0.04);
});
function paintMute() { document.body.classList.toggle('muted', muted); }
soundBtn.addEventListener('click', function() {
  muted = !muted;
  store.set('minas-mute', muted ? '1' : '0');
  paintMute();
  if (!muted) { audio(); tone(660, 0, 0.07, 'triangle', 0.06); }
});
themeBtn.addEventListener('click', function() {
  theme = (theme === 'night') ? 'day' : 'night';
  store.set('minas-theme', theme);
  paintTheme();
  if (theme === 'night') tone(340, 170, 0.14, 'triangle', 0.06);
  else tone(420, 660, 0.12, 'triangle', 0.06);
});
resetBtn.addEventListener('click', function() {
  tone(500, 240, 0.09, 'triangle', 0.05);
  newGame();
});
window.addEventListener('resize', fitCells);

// Permitir abandonar con tecla ESC
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && partidaId && !over) {
    abandonarPartida();
  }
});

// ---------- Arranque ----------
const MES = ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'];
const hoy = new Date();
todayEl.textContent = String(hoy.getDate()).padStart(2,'0') + ' ' + MES[hoy.getMonth()] + ' ' + hoy.getFullYear();

document.body.classList.add('buscaminas-page');
paintMute();
paintTheme();
cargarRecordPersonal();
newGame();
})();