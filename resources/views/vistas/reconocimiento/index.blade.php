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

        <div class="pb-8.5 flex flex-col gap-5 w-full ">
            
            <div class="inline-flex gap-3 justify-center items-center mb-0.5 text-ink">
                
                <flux:icon.camera variant="solid" class="size-8" />
            
                <h1 class="mt-1 font-display font-bold text-2xl">
                    {{ __('Consejos para una buena foto') }}
                </h1>
            </div>


            
            <div class="flex flex-col gap-3">

                <flux:callout class="bg-[#ffeebf]! text-[#ba4e00]! border-none!">
                    <x-slot name="icon">
                        <flux:icon.key-round class="size-5" />
                    </x-slot>
                    <flux:callout.heading class="text-[#ba4e00]!">Permisos de cámara</flux:callout.heading>
                    <flux:callout.text class="text-[#ba4e00]!">
                        <p>Asegúrate de otorgar permisos a tu navegador para acceder a la cámara y poder tomar fotos.</p>
                    </flux:callout.text>
                </flux:callout>

                <flux:callout class="bg-[#ccf2ff]! border-none!">
                    <x-slot name="icon">
                        <flux:icon.sun class="size-7 text-[#193cbf]!" />
                    </x-slot>
                    <flux:callout.heading class="text-[#193cbf]!">Buena iluminación</flux:callout.heading>
                    <flux:callout.text class="text-[#193cbf]!">
                        <p>Busca luz natural e indirecta. Evita usar flash.</p>
                    </flux:callout.text>
                </flux:callout>

                <flux:callout class="bg-[#f2f7f2]! border-none!">
                    <x-slot name="icon">
                        <flux:icon.eye class="size-5 text-[#80b67e]!" />
                    </x-slot>
                    <flux:callout.heading class="text-[#80b67e]!">Posición y enfoque</flux:callout.heading>
                    <flux:callout.text class="text-[#80b67e]!">
                        <p>Acércate para que el hongo ocupe la mayor parte de la imagen, asegurando un enfoque nítido.</p>
                    </flux:callout.text>
                </flux:callout>

                <flux:callout class="bg-[#faebeb]! border-none!">
                    <x-slot name="icon">
                        <flux:icon.sparkles class="size-5 text-[#cb3434]!" />
                    </x-slot>
                    <flux:callout.heading class="text-[#cb3434]!">Limpia tu lente</flux:callout.heading>
                    <flux:callout.text class="text-[#cb3434]!">
                        <p>Un lente limpio previene fotos borrosas e incrementa significativamente la precisión del reconocimiento.</p>
                    </flux:callout.text>
                </flux:callout>

                <flux:callout class="bg-[#f3e8ff]! border-none!">
                    <x-slot name="icon">
                        <flux:icon.magnifying-glass-plus class="size-5 text-[#7e22ce]!" />
                    </x-slot>
                    <flux:callout.heading class="text-[#7e22ce]!">Captura los detalles clave</flux:callout.heading>
                    <flux:callout.text class="text-[#7e22ce]!">
                        <p>Intenta incluir la parte superior del sombrero y, si es visible, las láminas o poros debajo del mismo.</p>
                    </flux:callout.text>
                </flux:callout>
                
            </div>

        </div>
    </div>
</x-layouts::app>
