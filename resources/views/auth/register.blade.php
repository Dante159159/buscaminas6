<x-guest-layout>
    <div class="retro-sheet">
        <h2 class="retro-h3 retro-text-center retro-mb-2">▸ Alta de Operador</h2>
        <div class="retro-rule dbl retro-mb-4"></div>

        <form method="POST" action="{{ route('register') }}" class="retro-form">
            @csrf

            <div class="retro-field">
                <label for="name" class="retro-label">▸ Nombre de operador</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="retro-input @error('name') error @enderror" placeholder="Sargento Pérez">
                @error('name') <div class="retro-error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="retro-field">
                <label for="email" class="retro-label">▸ Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="retro-input @error('email') error @enderror" placeholder="operador@campo.mil">
                @error('email') <div class="retro-error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="retro-field">
                <label for="password" class="retro-label">▸ Clave de acceso</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="retro-input @error('password') error @enderror" placeholder="••••••••">
                @error('password') <div class="retro-error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="retro-field">
                <label for="password-confirm" class="retro-label">▸ Confirmar clave</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="retro-input" placeholder="••••••••">
            </div>

            <div class="retro-rule"></div>

            <button type="submit" class="retro-btn primary lg retro-w-full">▸ Registrar Expediente</button>
        </form>

        <div class="retro-rule retro-mt-4"></div>
        <p class="retro-text-center retro-mt-2" style="font-family:var(--mono);font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-55)">
            ¿Ya tiene expediente? <a href="{{ route('login') }}" style="color:var(--red);text-decoration:none;font-weight:600">Acceder al sistema</a>
        </p>
    </div>
</x-guest-layout>