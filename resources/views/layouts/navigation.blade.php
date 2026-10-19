<nav class="retro-nav">
    <div class="retro-nav-inner">

        <!-- Brand -->
        <a href="{{ route('dashboard') }}" class="retro-nav-brand">
            <span>💣</span>
            <span>CAMPO MINADO<i class="dot">.</i></span>
        </a>

        <!-- Botón mobile -->
        <button class="retro-theme-btn retro-nav-mobile" id="btnMobileNav" style="display:none" title="Menú">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        </button>

        <!-- Links -->
        <ul class="retro-nav-links" id="navLinks">
            <li><a href="{{ route('dashboard') }}" class="retro-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('juego.index') }}" class="retro-nav-link {{ request()->routeIs('juego.index') ? 'active' : '' }}">Jugar</a></li>
            <li><a href="{{ route('records.index') }}" class="retro-nav-link {{ request()->routeIs('records.index') ? 'active' : '' }}">Récords</a></li>
            <li><a href="{{ route('historial.index') }}" class="retro-nav-link {{ request()->routeIs('historial.index') ? 'active' : '' }}">Historial</a></li>
        </ul>

        <!-- Actions -->
        <div class="retro-nav-actions">

            <!-- Botón tema -->
            <button class="retro-theme-btn" title="Cambiar tema">
                <svg class="i-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg class="i-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4.2"/><path d="M12 2.6v2.1M12 19.3v2.1M2.6 12h2.1M19.3 12h2.1M5.4 5.4l1.5 1.5M17.1 17.1l1.5 1.5M18.6 5.4l-1.5 1.5M6.9 17.1l-1.5 1.5"/></svg>
            </button>

            <!-- Dropdown usuario -->
            <div class="retro-dropdown" id="userDropdown">
                <button class="retro-btn sm" onclick="document.getElementById('userDropdown').classList.toggle('open')">
                    <span>{{ Auth::user()->name }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="retro-dropdown-menu">
                    <a href="{{ route('profile.edit') }}" class="retro-dropdown-item">▸ Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="retro-dropdown-item">▸ Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
// Cerrar dropdown al hacer clic fuera
document.addEventListener('click', function(e) {
    const dd = document.getElementById('userDropdown');
    if (dd && !dd.contains(e.target)) dd.classList.remove('open');
});

// Mobile nav toggle
const btnMobile = document.getElementById('btnMobileNav');
const navLinks = document.getElementById('navLinks');
function checkMobile() {
    btnMobile.style.display = window.innerWidth <= 768 ? 'grid' : 'none';
    if (window.innerWidth > 768) navLinks.classList.remove('open');
}
checkMobile();
window.addEventListener('resize', checkMobile);
btnMobile.addEventListener('click', function() {
    navLinks.classList.toggle('open');
});
</script>