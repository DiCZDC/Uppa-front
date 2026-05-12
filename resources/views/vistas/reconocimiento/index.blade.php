<x-layouts::app :title="__('Reconocimiento')">
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12">
        
        <div class="mb-8 flex flex-col gap-5">
             <div class="inline-flex gap-3 justify-center items-center  text-ink">
                
                <flux:icon.scan-qr-code class="size-8" />
            
                <h1 class="mt-1 font-display font-bold text-2xl">
                    {{ __('¡Escanea tu hongo ahora!') }}
                </h1>
            </div>

            <livewire:file.upload />
        </div>
    </div>
</x-layouts::app>
