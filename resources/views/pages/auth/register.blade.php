<x-layouts::auth :title="__('Crear cuenta')">
    <div class="flex flex-col gap-6">
        <div>
            <div class="text-[12px] font-bold tracking-[1.4px] text-olive uppercase">
                {{ __('Paso 1 de 2') }}
            </div>
            <h1 class="mt-2 font-display text-[32px] font-medium leading-[1.1] tracking-[-0.8px] text-ink">
                {{ __('Crea tu cuenta') }}
            </h1>
            <p class="mt-2 text-[14px] leading-[1.5] text-ink-2">
                {{ __('Guarda tu historial de identificaciones y recibe alertas de plagas.') }}
            </p>
        </div>

        {{-- Progress bar --}}
        <div class="flex gap-1.5">
            <div class="h-1 flex-1 rounded-full bg-olive"></div>
            <div class="h-1 flex-1 rounded-full bg-line"></div>
        </div>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-3">
            @csrf

            <flux:input
                name="name"
                :label="__('Nombre completo')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Nombre completo')"
                icon="user"
            />

            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@ejemplo.com"
                icon="envelope"
            />

            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Contraseña')"
                viewable
                icon="lock-closed"
            />

            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmar contraseña')"
                viewable
                icon="lock-closed"
            />

            <button
                type="submit"
                data-test="register-user-button"
                class="mt-4 inline-flex h-[50px] w-full items-center justify-center gap-2 rounded-2xl bg-olive px-5 text-[15px] font-semibold tracking-[-0.1px] text-white ms-shadow-cta transition hover:brightness-105"
            >
                {{ __('Continuar') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>

        <div class="text-center text-[14px] text-ink-2">
            {{ __('¿Ya tienes cuenta?') }}
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-olive">
                {{ __('Inicia sesión') }}
            </a>
        </div>
    </div>
</x-layouts::auth>
