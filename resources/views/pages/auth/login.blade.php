<x-layouts::auth :title="__('Iniciar sesión')">
    <div class="flex flex-col gap-7">
        <div>
            <h1 class="font-display text-[34px] font-medium leading-[1.05] tracking-[-1px] text-ink">
                {{ __('Bienvenido') }}<br>{{ __('de vuelta') }}
            </h1>
            <p class="mt-3 text-[14px] leading-[1.5] text-ink-2">
                {{ __('Inicia sesión para continuar identificando hongos.') }}
            </p>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-3">
            @csrf

            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@ejemplo.com"
                icon="envelope"
            />

            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Contraseña')"
                viewable
                icon="lock-closed"
            />

            <div class="flex items-center justify-between pt-1">
                <flux:checkbox name="remember" :label="__('Recordarme')" :checked="old('remember')" />

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        wire:navigate
                        class="text-[13px] font-semibold text-olive transition hover:brightness-110"
                    >
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
            </div>

            <button
                type="submit"
                data-test="login-button"
                class="mt-4 inline-flex h-[50px] w-full items-center justify-center gap-2 rounded-2xl bg-olive px-5 text-[15px] font-semibold tracking-[-0.1px] text-white ms-shadow-cta transition hover:brightness-105"
            >
                {{ __('Iniciar sesión') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-[14px] text-ink-2">
                {{ __('¿No tienes cuenta?') }}
                <a href="{{ route('register') }}" wire:navigate class="font-bold text-olive">
                    {{ __('Regístrate') }}
                </a>
            </div>
        @endif
    </div>
</x-layouts::auth>
