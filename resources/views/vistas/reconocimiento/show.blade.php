<x-layouts::app :title="__('Reconocimiento')">
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12">
        <a
            href="{{ route('reconocimiento.index') }}"
            wire:navigate
            class="inline-flex items-center gap-2 text-[13px] font-semibold text-ink-2 transition hover:text-ink"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                <path d="M19 12H5M11 6l-6 6 6 6" />
            </svg>
            {{ __('Volver al historial') }}
        </a>

        <div class="mt-4">
            <div class="text-[12px] font-bold tracking-[1.2px] text-ink-3 uppercase">
                {{ __('Reconocimiento') }} #{{ $id }}
            </div>
            <h1 class="mt-1.5 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px] text-ink">
                {{ __('Detalle del reconocimiento') }}
            </h1>
        </div>

        <div class="mt-6 rounded-3xl border border-line-2 bg-card p-6 ms-shadow-card">
            <p class="text-[14px] leading-[1.55] text-ink-2">
                {{ __('Esta vista mostrará el detalle del reconocimiento guardado, incluyendo la foto, la especie identificada, plagas detectadas y los tratamientos sugeridos.') }}
            </p>
        </div>
    </div>
</x-layouts::app>
