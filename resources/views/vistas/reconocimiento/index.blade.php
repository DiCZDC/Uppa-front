<x-layouts::app :title="__('Reconocimiento')">
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12">
        <div class="pb-3">
            <div class="text-[12px] font-bold tracking-[1.2px] text-ink-3 uppercase">
                {{ __('Identificación de hongos') }}
            </div>
            <h1 class="mt-1.5 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px] text-ink">
                {{ __('Escanea un hongo') }}
            </h1>
            <p class="mt-1.5 max-w-md text-[14px] leading-[1.5] text-ink-2">
                {{ __('Sube una foto del ejemplar bien iluminado. La IA identificará la especie, posibles plagas y tratamientos.') }}
            </p>
        </div>

        <livewire:file.upload />
    </div>
</x-layouts::app>
