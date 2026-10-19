<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Campo Minado') }}
        </h2>
    </x-slot>

    <style>
    :root{
      /* ---- MODO DÍA (papel a la luz del escritorio) ---- */
      --paper:#eae0c4; --desk:#c9bc95;
      --ink:#2a2419; --ink-55:rgba(42,36,25,.55); --ink-30:rgba(42,36,25,.3); --ink-12:rgba(42,36,25,.12);
      --line:rgba(42,36,25,.30);
      --red:#a33327; --green:#55683c;
      --cell:#d7c9a1; --cell-h:#e4d8b4; --open:#f0e8d2;
      --cell-dot:rgba(42,36,25,.14);
      --cell-inset-l:rgba(255,255,255,.3); --cell-inset-d:rgba(42,36,25,.16);
      --open-shadow:rgba(42,36,25,.10); --press-shadow:rgba(42,36,25,.28);
      --shadow-strong:rgba(42,36,25,.5); --shadow-soft:rgba(42,36,25,.28); --shadow-12:rgba(42,36,25,.12);
      --vignette:rgba(42,36,25,.22);
      --dbl-rule:rgba(42,36,25,.6); --btn-hover:rgba(42,36,25,.08);
      --board-bg:rgba(255,255,255,.16);
      --wrong:#ecd9c2; --dead-a:#f3ddc8; --dead-b:#e2c4ad;
      --flash:rgba(163,51,39,.14); --shard-dark:#3a3226;
      --n1:#33557d;--n2:#4a6b3a;--n3:#b03a2c;--n4:#41386b;
      --n5:#7a2f24;--n6:#2f6b66;--n7:#2a2419;--n8:#6b6252;
      --stamp-blend:multiply;
      --mono:'IBM Plex Mono',monospace; --type:'Special Elite',serif; --stencil:'Saira Stencil One',sans-serif;
      --grain:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='g'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' seed='4'/><feColorMatrix values='0 0 0 0 0.16 0 0 0 0 0.13 0 0 0 0 0.08 0 0 0 0.07 0'/></filter><rect width='160' height='160' filter='url(%23g)'/></svg>");
      --wear:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='240' height='240'><filter id='w'><feTurbulence type='fractalNoise' baseFrequency='0.14' numOctaves='3' seed='11'/><feColorMatrix values='0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0.85 0.85 0.85 0 -0.62'/></filter><rect width='240' height='240' filter='url(%23w)'/></svg>");
    }
    body.night.buscaminas-page{
      /* ---- MODO NOCHE ---- */
      --paper:#292318; --desk:#141009;
      --ink:#e8dcba; --ink-55:rgba(232,220,186,.55); --ink-30:rgba(232,220,186,.3); --ink-12:rgba(232,220,186,.12);
      --line:rgba(232,220,186,.26);
      --red:#d9604a; --green:#97b168;
      --cell:#3a3323; --cell-h:#4c4330; --open:#1d1912;
      --cell-dot:rgba(0,0,0,.3);
      --cell-inset-l:rgba(255,255,255,.06); --cell-inset-d:rgba(0,0,0,.35);
      --open-shadow:rgba(0,0,0,.4); --press-shadow:rgba(0,0,0,.5);
      --shadow-strong:rgba(0,0,0,.65); --shadow-soft:rgba(0,0,0,.5); --shadow-12:rgba(0,0,0,.35);
      --vignette:rgba(0,0,0,.55);
      --dbl-rule:rgba(232,220,186,.5); --btn-hover:rgba(232,220,186,.09);
      --board-bg:rgba(0,0,0,.18);
      --wrong:#54301f; --dead-a:#f5d8bd; --dead-b:#4e2a19;
      --flash:rgba(255,120,80,.15); --shard-dark:#0d0b07;
      --n1:#8fb4e3;--n2:#a9cd8b;--n3:#ef7a63;--n4:#b9a7f2;
      --n5:#e6907c;--n6:#7ccac2;--n7:#e8dcba;--n8:#b3a98d;
      --stamp-blend:screen;
      --grain:url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='g'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' seed='4'/><feColorMatrix values='0 0 0 0 0.9 0 0 0 0 0.85 0 0 0 0 0.68 0 0 0 0.05 0'/></filter><rect width='160' height='160' filter='url(%23g)'/></svg>");
    }
    body.buscaminas-page{
      background:var(--desk); background-image:var(--grain);
      color:var(--ink); font-family:var(--type);
      transition:background-color .35s ease,color .35s ease;
    }
    body.buscaminas-page::after{content:'';position:fixed;inset:0;pointer-events:none;box-shadow:inset 0 0 170px var(--vignette);z-index:1}
    body.buscaminas-page ::selection{background:var(--ink);color:var(--paper)}

    .buscaminas-page .sheet{
      width:min(1160px,100%);
      margin:0 auto;
      background:var(--paper); background-image:var(--grain);
      border:1px solid var(--shadow-strong);
      box-shadow:0 2px 0 var(--shadow-12), 0 18px 44px var(--shadow-soft);
      padding:clamp(16px,3.4vw,32px) clamp(14px,3.4vw,38px) clamp(16px,3vw,24px);
      transition:background-color .35s ease,border-color .35s ease,box-shadow .35s ease;
      position:relative; z-index:2;
    }

    .buscaminas-page .masthead{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;flex-wrap:wrap}
    .buscaminas-page .file-line{font-family:var(--mono);font-size:10.5px;letter-spacing:.22em;color:var(--ink-55);text-transform:uppercase}
    .buscaminas-page .file-line b{color:var(--red);font-weight:600}
    .buscaminas-page h1.title{font-family:var(--stencil);font-weight:400;font-size:clamp(34px,5.6vw,58px);line-height:.98;letter-spacing:.015em;margin:6px 0 5px}
    .buscaminas-page h1.title .dot{color:var(--red);font-style:normal}
    .buscaminas-page .subline{font-size:12px;letter-spacing:.14em;color:var(--ink-55)}
    .buscaminas-page .mast-right{display:flex;align-items:center;gap:16px;padding-bottom:8px}
    .buscaminas-page .official{font-family:var(--mono);font-size:9px;letter-spacing:.32em;color:var(--red);border:2px solid var(--red);padding:4px 6px 3px 10px;transform:rotate(-4deg);opacity:.75;white-space:nowrap}
    .buscaminas-page .chip{font-family:var(--mono);font-size:10px;letter-spacing:.24em;border:1.5px solid currentColor;padding:6px 8px 5px 12px;display:inline-flex;align-items:center;gap:8px;white-space:nowrap}
    .buscaminas-page .chip::before{content:'';width:7px;height:7px;border-radius:50%;background:currentColor;flex:none}
    .buscaminas-page .chip.idle{color:var(--ink)} .buscaminas-page .chip.warn{color:var(--red)} .buscaminas-page .chip.ok{color:var(--green)}
    .buscaminas-page .chip.warn{animation:chipBlink 1s steps(2) infinite}
    @keyframes chipBlink{50%{opacity:.5}}

    .buscaminas-page .rule{border-top:1px solid var(--line);margin-top:14px}
    .buscaminas-page .rule.dbl{border-top:3px double var(--dbl-rule)}

    .buscaminas-page .instruments{display:flex;align-items:center;gap:clamp(12px,2.4vw,26px);flex-wrap:wrap;padding:13px 0}
    .buscaminas-page .meter{display:flex;flex-direction:column;gap:3px}
    .buscaminas-page .meter+.meter{border-left:1px solid var(--line);padding-left:clamp(12px,2.4vw,26px)}
    .buscaminas-page .lbl{font-family:var(--mono);font-size:9px;letter-spacing:.3em;color:var(--ink-55);text-transform:uppercase}
    .buscaminas-page .val{font-family:var(--mono);font-weight:700;font-size:clamp(20px,2.6vw,27px);line-height:1;font-variant-numeric:tabular-nums;min-width:3.2ch}
    .buscaminas-page .val.rec-flash{animation:recFlash .8s steps(2) 3}
    @keyframes recFlash{50%{color:var(--green)}}
    .buscaminas-page .spacer{flex:1}
    .buscaminas-page .tools{display:flex;gap:8px}
    .buscaminas-page .tbtn{appearance:none;width:36px;height:36px;border:1.5px solid var(--ink);background:transparent;color:var(--ink);cursor:pointer;display:grid;place-items:center;padding:0;transition:background .12s,color .12s,border-color .25s}
    .buscaminas-page .tbtn:hover{background:var(--ink);color:var(--paper)}
    .buscaminas-page .tbtn:active{transform:translateY(1px)}
    .buscaminas-page .tbtn svg{width:17px;height:17px;display:block}
    .buscaminas-page .tbtn.primary{background:var(--ink);color:var(--paper);width:auto;padding:0 13px;gap:8px;display:flex;align-items:center;font-family:var(--mono);font-size:10px;letter-spacing:.16em}
    .buscaminas-page .tbtn.primary:hover{background:var(--red);border-color:var(--red)}
    .buscaminas-page .i-flag{display:none}
    body.buscaminas-page.flag-mode .i-shovel{display:none}
    body.buscaminas-page.flag-mode .i-flag{display:block}
    body.buscaminas-page.flag-mode #modeBtn{background:var(--ink);color:var(--paper)}
    .buscaminas-page .i-mute{display:none}
    body.buscaminas-page.muted .i-snd{display:none}
    body.buscaminas-page.muted .i-mute{display:block}
    .buscaminas-page .i-sun{display:none}
    body.buscaminas-page.night .i-sun{display:block}
    body.buscaminas-page.night .i-moon{display:none}

    .buscaminas-page .board-outer{position:relative;margin-top:14px}
    .buscaminas-page .board-scroll{overflow-x:auto;overflow-y:hidden;padding:2px 2px 8px}
    .buscaminas-page .board-scroll::-webkit-scrollbar{height:8px}
    .buscaminas-page .board-scroll::-webkit-scrollbar-thumb{background:var(--ink-30)}
    .buscaminas-page .board-scroll::-webkit-scrollbar-track{background:var(--ink-12)}
    .buscaminas-page .board{
      display:grid;
      grid-template-columns:26px repeat(20,28px);
      width:max-content;margin:0 auto;
      border:1.5px solid var(--ink);
      background:var(--board-bg);
      box-shadow:5px 6px 0 var(--shadow-12);
      user-select:none;-webkit-user-select:none;touch-action:manipulation;
      transition:background-color .35s ease,border-color .35s ease;
    }
    .buscaminas-page .ax{display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:9px;color:var(--ink-55);height:18px}
    .buscaminas-page .ax.colx{border-bottom:1px solid var(--ink-30)}
    .buscaminas-page .ax.rowx{align-self:stretch;border-right:1px solid var(--ink-30)}
    .buscaminas-page .cell{
      appearance:none;border:1px solid var(--line);
      width:var(--cell,28px);height:var(--cell,28px);
      background:var(--cell);
      background-image:radial-gradient(var(--cell-dot) .5px,transparent .6px);
      background-size:5px 5px;
      box-shadow:inset 1px 1px 0 var(--cell-inset-l),inset -1px -1px 0 var(--cell-inset-d);
      display:grid;place-items:center;padding:0;cursor:crosshair;position:relative;
      font-family:var(--mono);font-weight:600;font-size:calc(var(--cell,28px)*.5);color:var(--ink);
      transition:background-color .18s ease,color .18s ease,border-color .25s ease;
    }
    .buscaminas-page .cell:hover:not(.open):not(.flagged){background-color:var(--cell-h)}
    .buscaminas-page .cell:active:not(.open){box-shadow:inset 2px 2px 3px var(--press-shadow)}
    .buscaminas-page .cell.open{background:var(--open);background-image:none;border-color:var(--ink-12);box-shadow:inset 0 1px 4px var(--open-shadow);cursor:default}
    .buscaminas-page .cell.flagged{background-color:var(--cell-h)}
    .buscaminas-page .cell.wrong{background:var(--wrong)}
    .buscaminas-page .cell svg{width:calc(var(--cell,28px)*.6);height:calc(var(--cell,28px)*.6);display:block;pointer-events:none}
    .buscaminas-page .cell.flagged svg{animation:plant .22s cubic-bezier(.2,1.4,.4,1) both;transform-origin:50% 85%}
    @keyframes plant{0%{transform:translateY(7px) scale(.5);opacity:0}60%{transform:translateY(-2px) scale(1.08)}}
    .buscaminas-page .num{line-height:1;animation:pop .16s ease-out both}
    @keyframes pop{from{transform:scale(.3);opacity:0}}
    .buscaminas-page .n1{color:var(--n1)}.buscaminas-page .n2{color:var(--n2)}.buscaminas-page .n3{color:var(--n3)}.buscaminas-page .n4{color:var(--n4)}
    .buscaminas-page .n5{color:var(--n5)}.buscaminas-page .n6{color:var(--n6)}.buscaminas-page .n7{color:var(--n7)}.buscaminas-page .n8{color:var(--n8)}
    .buscaminas-page .cell.mine{color:var(--ink)}
    .buscaminas-page .cell.mine.dead{color:var(--red);animation:deadPulse .55s ease}
    @keyframes deadPulse{0%{background:var(--dead-a)}45%{background:var(--red)}100%{background:var(--dead-b)}}
    .buscaminas-page .cell .cross{position:absolute;inset:0;width:100%;height:100%}
    .buscaminas-page .cell .cross path{stroke:var(--red);stroke-width:2.6;stroke-linecap:round;fill:none}

    .buscaminas-page .fx{position:absolute;inset:0;pointer-events:none;z-index:5}
    .buscaminas-page .ring{position:absolute;width:16px;height:16px;border:2.5px solid var(--red);border-radius:50%;transform:translate(-50%,-50%) scale(0);animation:ringOut .7s cubic-bezier(.2,.7,.3,1) forwards}
    @keyframes ringOut{0%{transform:translate(-50%,-50%) scale(0);opacity:.95}100%{transform:translate(-50%,-50%) scale(13);opacity:0}}
    .buscaminas-page .shard{position:absolute;width:6px;height:3px}
    .buscaminas-page .flash{position:absolute;inset:0;background:var(--flash);opacity:0;pointer-events:none;z-index:4}
    .buscaminas-page .flash.go{animation:flashFade .55s ease-out forwards}
    @keyframes flashFade{0%{opacity:1}100%{opacity:0}}
    .buscaminas-page .shakeIt{animation:shakeIt .5s ease}
    @keyframes shakeIt{
      10%{transform:translate(-6px,2px) rotate(-.4deg)}
      25%{transform:translate(5px,-3px) rotate(.35deg)}
      40%{transform:translate(-4px,2px) rotate(-.25deg)}
      55%{transform:translate(3px,-1px)}
      70%{transform:translate(-2px,1px)}
      85%{transform:translate(1px,0)}
    }

    .buscaminas-page .gridnote{display:flex;align-items:center;gap:12px;margin:8px 2px 0;font-family:var(--mono);font-size:9px;letter-spacing:.26em;color:var(--ink-55);text-transform:uppercase;white-space:nowrap}
    .buscaminas-page .gridnote .ln{height:1px;background:var(--line);width:22px;flex:none}
    .buscaminas-page .gridnote .grow{flex:1}
    .buscaminas-page #posInfo{color:var(--red);min-width:9ch;text-align:right}

    .buscaminas-page .stamp-layer{position:fixed;inset:0;display:grid;place-items:center;pointer-events:none;z-index:50;padding:20px}
    .buscaminas-page .stamp{
      font-family:var(--stencil);color:var(--red);
      font-size:clamp(36px,8.5vw,86px);line-height:1;text-align:center;
      border:5px solid currentColor;outline:1.5px solid currentColor;outline-offset:4px;border-radius:8px;
      padding:.18em .5em .14em;letter-spacing:.05em;
      transform:rotate(-8deg);mix-blend-mode:var(--stamp-blend);
      -webkit-mask-image:var(--wear);mask-image:var(--wear);
      -webkit-mask-size:260px;mask-size:260px;
      opacity:0;max-width:94vw;
    }
    .buscaminas-page .stamp.on{animation:stampIn .42s cubic-bezier(.2,1.4,.36,1) forwards}
    @keyframes stampIn{
      0%{opacity:0;transform:rotate(-18deg) scale(2.6)}
      55%{opacity:.95;transform:rotate(-7deg) scale(.94)}
      78%{transform:rotate(-8.5deg) scale(1.04)}
      100%{opacity:.93;transform:rotate(-8deg) scale(1)}
    }
    .buscaminas-page .stamp.green{color:var(--green)}
    .buscaminas-page .stamp .sub{display:block;font-family:var(--mono);font-size:clamp(10px,1.6vw,13px);letter-spacing:.42em;margin-top:.5em;font-weight:600}

    .buscaminas-page .notes{margin-top:16px;padding-top:12px;border-top:1px solid var(--line)}
    .buscaminas-page .notes-row{display:flex;flex-wrap:wrap;gap:6px 28px;font-size:11.5px;color:var(--ink-55);line-height:1.5}
    .buscaminas-page .notes-row b{color:var(--red);font-family:var(--mono);font-size:10px;margin-right:5px}
    .buscaminas-page .pageno{display:flex;justify-content:space-between;gap:10px;margin-top:10px;font-family:var(--mono);font-size:9px;letter-spacing:.28em;color:var(--ink-55);text-transform:uppercase;flex-wrap:wrap}

    @media(max-width:680px){
      .buscaminas-page .tbtn.primary span{display:none}
      .buscaminas-page .tbtn.primary{width:36px;padding:0;justify-content:center}
      .buscaminas-page .spacer{display:none}
      .buscaminas-page .mast-right{padding-bottom:0}
    }
    </style>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Saira+Stencil+One&family=Special+Elite&display=swap" rel="stylesheet">

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <main class="sheet">
              <header class="masthead">
                <div>
                  <p class="file-line"><b>EXPEDIENTE Nº 1943-B</b> · SECCIÓN 4 · DESPEJE DE TERRENO · <span id="today"></span></p>
                  <h1 class="title">CAMPO MINADO<i class="dot">.</i></h1>
                  <p class="subline">PROTOCOLO DE DESPEJE · 20×20 · 70 MINAS · NIVEL SARGENTO</p>
                </div>
                <div class="mast-right">
                  <span class="official">USO OFICIAL</span>
                  <span class="chip idle" id="chip">EN ESPERA</span>
                </div>
              </header>

              <div class="rule dbl"></div>

              <section class="instruments">
                <div class="meter"><span class="lbl">Minas</span><span class="val" id="mineVal">070</span></div>
                <div class="meter"><span class="lbl">Tiempo</span><span class="val" id="timeVal">000</span></div>
                <div class="meter"><span class="lbl">Récord</span><span class="val" id="bestVal">—</span></div>
                <div class="spacer"></div>
                <div class="tools">
                  <button class="tbtn" id="modeBtn" title="Modo excavar / marcar (táctil)">
                    <svg class="i-shovel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 3.5V13"/><path d="M9.5 3.5h5"/><path d="M8.5 13h7l-.9 4.6a3.6 3.6 0 0 1-5.2 0z" fill="currentColor" stroke="none"/></svg>
                    <svg class="i-flag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 21V4"/><path d="M6 4.5 16 7.6 6 10.8z" fill="currentColor" stroke="none"/></svg>
                  </button>
                  <button class="tbtn" id="soundBtn" title="Sonido sí / no">
                    <svg class="i-snd" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 9.5v5h3.5L12 18.5v-13L7.5 9.5H4z" fill="currentColor" stroke="none"/><path d="M15.5 9.2a4.4 4.4 0 0 1 0 5.6"/><path d="M18 7a8 8 0 0 1 0 10"/></svg>
                    <svg class="i-mute" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 9.5v5h3.5L12 18.5v-13L7.5 9.5H4z" fill="currentColor" stroke="none"/><path d="M15.5 9.5l5 5M20.5 9.5l-5 5"/></svg>
                  </button>
                  <button class="tbtn" id="themeBtn" title="Cambiar a modo noche">
                    <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.6v2.1M12 19.3v2.1M2.6 12h2.1M19.3 12h2.1M5.4 5.4l1.5 1.5M17.1 17.1l1.5 1.5M18.6 5.4l-1.5 1.5M6.9 17.1l-1.5 1.5"/></svg>
                  </button>
                  <button class="tbtn primary" id="resetBtn" title="Nueva partida">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12a8 8 0 1 1-2.5-5.8"/><path d="M18.5 2.8v3.9h-3.9"/></svg>
                    <span>NUEVA MISIÓN</span>
                  </button>
                </div>
              </section>

              <div class="rule"></div>

              <section class="board-outer" id="outer">
                <div class="board-scroll" id="scroller">
                  <div class="board" id="board"></div>
                </div>
                <div class="fx" id="fx"></div>
                <div class="flash" id="flash"></div>
              </section>

              <p class="gridnote">
                <span class="ln"></span>
                <span id="gridInfo"></span>
                <span class="ln grow"></span>
                <span id="posInfo">POS — —</span>
                <span class="ln"></span>
              </p>

              <footer class="notes">
                <div class="notes-row">
                  <span><b>01</b> Clic izquierdo excava la casilla.</span>
                  <span><b>02</b> Clic derecho —o pulsación larga en táctil— planta una bandera.</span>
                  <span><b>03</b> Clic sobre un número despejado, con sus banderas colocadas, abre las vecinas.</span>
                  <span><b>04</b> El botón de luna / sol alterna entre modo día y modo noche.</span>
                </div>
                <div class="pageno"><span>FORMULARIO FM-1943 · REV. C</span><span>PÁG. 01 / 01</span></div>
              </footer>
            </main>

            <div class="stamp-layer" id="stampLayer"></div>
        </div>
    </div>

    <script>window.CSRF_TOKEN = '{{ csrf_token() }}';</script>
    <script src="{{ asset('js/buscaminas.js') }}"></script>
</x-app-layout>