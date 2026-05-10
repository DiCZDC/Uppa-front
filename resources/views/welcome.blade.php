<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-grape text-cream">
    @auth
        <script>window.location.replace(@json(route('dashboard')));</script>
    @endauth

    <main class="relative mx-auto flex min-h-screen w-full max-w-md flex-col overflow-hidden">
        {{-- Radial dark gradient backdrop --}}
        <div
            class="pointer-events-none absolute inset-0"
            style="background: radial-gradient(120% 70% at 50% 30%, #3c3546 0%, #2a2434 60%, #1d1828 100%);"
        ></div>

        {{-- Spore dots --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            @for ($i = 0; $i < 40; $i++)
                @php
                    $left = ($i * 73) % 100;
                    $top = ($i * 37) % 100;
                    $size = 2 + ($i % 3);
                    $isGold = $i % 5 === 0;
                @endphp
                <div
                    class="absolute rounded-full opacity-60"
                    style="left: {{ $left }}%; top: {{ $top }}%; width: {{ $size }}px; height: {{ $size }}px; background: {{ $isGold ? '#f9cb43' : 'rgba(246,239,219,0.25)' }};"
                ></div>
            @endfor
        </div>

        {{-- Hero --}}
        <div class="relative z-10 flex flex-1 flex-col items-center justify-center px-8 pt-20 pb-12 text-center">
            <x-app-logo-icon mode="color" class="size-[120px]" />
            <h1 class="mt-7 font-display text-[44px] font-medium leading-none tracking-[-1px] text-cream">
                MicoScan
            </h1>
            <p class="mt-4 max-w-[280px] text-[15px] leading-[1.5] text-cream/70">
                Identifica hongos comestibles y detecta enfermedades con inteligencia artificial.
            </p>
        </div>

        {{-- CTA stack --}}
        <div class="relative z-10 flex flex-col gap-3 px-6 pb-16">
            <a
                href="{{ route('register') }}"
                class="inline-flex h-[50px] items-center justify-center rounded-2xl bg-gold px-5 text-[15px] font-semibold tracking-[-0.1px] text-grape ms-shadow-cta transition hover:brightness-105"
                style="box-shadow: 0 6px 14px rgba(249,203,67,0.55);"
            >
                Crear cuenta
            </a>
            <a
                href="{{ route('login') }}"
                class="inline-flex h-[50px] items-center justify-center rounded-2xl border border-cream/25 bg-transparent px-5 text-[15px] font-semibold tracking-[-0.1px] text-cream transition hover:bg-cream/5"
            >
                Iniciar sesión
            </a>
            <p class="mt-2 text-center text-[12px] text-cream/50">
                Al continuar aceptas los Términos y la Política de privacidad
            </p>
        </div>
    </main>

    @fluxScripts
</body>
</html>
