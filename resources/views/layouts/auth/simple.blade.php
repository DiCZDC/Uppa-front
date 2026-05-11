<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-bg text-ink">
    <div class="flex min-h-svh flex-col items-stretch sm:items-center sm:justify-center sm:py-10">
        <main class="relative flex w-full max-w-md flex-1 flex-col px-6 pt-14 pb-10 sm:flex-none sm:rounded-3xl sm:bg-card sm:px-8 sm:py-10 sm:ms-shadow-card">
            <a
                href="{{ route('home') }}"
                class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-line bg-card text-ink transition hover:bg-cream/40"
                aria-label="{{ __('Volver') }}"
                wire:navigate
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <path d="M19 12H5M11 6l-6 6 6 6" />
                </svg>
            </a>

            <a href="{{ route('home') }}" class="mt-7 inline-flex items-center gap-3" wire:navigate>
                <x-app-logo-icon mode="color" class="size-9" />
                <span class="font-display text-[22px] font-medium tracking-[-0.4px] text-ink">Úppa</span>
            </a>

            <div class="mt-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
