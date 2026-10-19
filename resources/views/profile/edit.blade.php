<x-app-layout>
    <div class="retro-max-w-2xl retro-mx-auto">
        <div class="retro-sheet">
            <p class="retro-file-line"><b>EXPEDIENTE PERSONAL</b> · SECCIÓN 4</p>
            <h1 class="retro-h1 retro-mt-2">PERFIL DE OPERADOR<i class="dot">.</i></h1>
            <p class="retro-subline">Actualización de datos personales</p>
            <div class="retro-rule dbl retro-mt-4 retro-mb-4"></div>

            <section>
                <h2 class="retro-h3 retro-mb-4">▸ Información del operador</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="retro-form">
                    @csrf @method('patch')
                    <div class="retro-field">
                        <label for="name" class="retro-label">▸ Nombre</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="retro-input @error('name') error @enderror">
                        @error('name') <div class="retro-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="retro-field">
                        <label for="email" class="retro-label">▸ Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="retro-input @error('email') error @enderror">
                        @error('email') <div class="retro-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="retro-btn primary">▸ Guardar cambios</button>
                </form>
            </section>

            <div class="retro-rule retro-mt-6 retro-mb-6"></div>

            <section>
                <h2 class="retro-h3 retro-mb-4">▸ Cambiar clave de acceso</h2>
                <form method="POST" action="{{ route('password.update') }}" class="retro-form">
                    @csrf @method('patch')
                    <div class="retro-field">
                        <label for="current_password" class="retro-label">▸ Clave actual</label>
                        <input id="current_password" type="password" name="current_password" required class="retro-input @error('current_password') error @enderror">
                        @error('current_password') <div class="retro-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="retro-field">
                        <label for="password" class="retro-label">▸ Nueva clave</label>
                        <input id="password" type="password" name="password" required class="retro-input @error('password') error @enderror">
                        @error('password') <div class="retro-error-msg">{{ $message }}</div> @enderror
                    </div>
                    <div class="retro-field">
                        <label for="password_confirmation" class="retro-label">▸ Confirmar nueva clave</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="retro-input">
                    </div>
                    <button type="submit" class="retro-btn primary">▸ Actualizar clave</button>
                </form>
            </section>

            <div class="retro-rule retro-mt-6 retro-mb-6"></div>

            <section>
                <h2 class="retro-h3 retro-mb-4" style="color:var(--red)">▸ Cerrar expediente</h2>
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Eliminar definitivamente su expediente?')">
                    @csrf @method('delete')
                    <button type="submit" class="retro-btn danger">▸ Eliminar cuenta</button>
                </form>
            </section>

            <div class="retro-pageno retro-mt-6">
                <span>FORMULARIO FM-1943 · REV. C</span>
                <span>PÁG. 01 / 01</span>
            </div>
        </div>
    </div>
</x-app-layout>