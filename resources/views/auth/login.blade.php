<x-guest-layout>
    <div class="retro-sheet">
        <h2 class="retro-h3 retro-text-center retro-mb-2">▸ Acceso al Sistema</h2>
        <div class="retro-rule dbl retro-mb-4"></div>

        @if (session('status'))
            <div class="retro-alert success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="retro-form">
            @csrf

            <div class="retro-field">
                <label for="email" class="retro-label">▸ Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="retro-input @error('email') error @enderror" placeholder="operador@campo.mil">
                @error('email') <div class="retro-error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="retro-field">
                <label for="password" class="retro-label">▸ Clave de acceso</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="retro-input @error('password') error @enderror" placeholder="••••••••">
                @error('password') <div class="retro-error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="retro-field" style="flex-direction:row;align-items:center;gap:8px">
                <input id="remember" type="checkbox" name="remember" class="retro-checkbox">
                <label for="remember" class="retro-label" style="margin:0">Mantener sesión</label>
            </div>

            <div class="retro-rule"></div>

            <button type="submit" class="retro-btn primary lg retro-w-full">▸ Iniciar Misión</button>

            <p class="retro-text-center retro-mt-2" style="font-family:var(--mono);font-size:11px;letter-spacing:.14em;text-transform:uppercase">
                <a href="{{ route('password.request') }}" style="color:var(--ink-55);text-decoration:none">¿Olvidó su clave?</a>
            </p>
        </form>

        <div class="retro-rule retro-mt-4"></div>
        <p class="retro-text-center retro-mt-2" style="font-family:var(--mono);font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-55)">
            ¿Sin expediente? <a href="{{ route('register') }}" style="color:var(--red);text-decoration:none;font-weight:600">Solicitar acceso</a>
        </p>
    </div>
</x-guest-layout>