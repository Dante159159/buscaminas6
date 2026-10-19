(function () {
  'use strict';

  // Aplicar tema inmediatamente (evita flash)
  const store = {
    get(k){ try { return window.localStorage.getItem(k); } catch(e){ return null; } },
    set(k,v){ try { window.localStorage.setItem(k,v); } catch(e){} }
  };

  let theme = store.get('minas-theme');
  if (theme !== 'day' && theme !== 'night') {
    theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'night' : 'day';
  }

  function aplicarTema() {
    document.body.classList.toggle('night', theme === 'night');
    document.body.classList.add('retro-body');
    const meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', theme === 'night' ? '#141009' : '#c9bc95');
    document.querySelectorAll('.retro-theme-btn').forEach(function(btn){
      btn.title = (theme === 'night') ? 'Cambiar a modo día' : 'Cambiar a modo noche';
    });
  }

  function toggleTema() {
    theme = (theme === 'night') ? 'day' : 'night';
    store.set('minas-theme', theme);
    aplicarTema();
  }

  // Aplicar lo antes posible
  aplicarTema();

  // Exponer globalmente
  window.RetroTema = { toggle: toggleTema, aplicar: aplicarTema, get: function(){ return theme; } };

  // Asignar a botones de tema cuando el DOM esté listo
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.retro-theme-btn').forEach(function (btn) {
      btn.addEventListener('click', toggleTema);
    });
  });
})();