<x-layouts::app :title="__('Inicio')">
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12">
        {{-- Greeting --}}
        <div class="pb-3 flex flex-col items-start gap-3.5 ">
            <div class="text-base text-ink-2">
                {{ __('¡Hola!') }}, {{ auth()->user()->name }} 
            </div>
            <div class="inline-flex gap-4 text-ink items-center">
                <flux:icon.scan-search class="size-12" />

                <h1 class="mt-1 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px]">
                    {{ __('¿Qué vamos') }}<br>{{ __('a analizar hoy?') }}
                </h1>
            </div>
        </div>

        {{-- Hero scan card --}}
        <div class="relative mt-3 flex flex-col overflow-hidden rounded-3xl bg-verde-claro! p-6 transition">
            <div>
                <flux:badge 
                {{-- class="text-white! bg-verde-fuerte!"  --}}
                color="lime" size="sm" icon="clock-alert">En menos de 10 segundos!</flux:badge>
            </div>
            <div class="mt-3.5 max-w-[240px] font-display text-2xl font-bold text-verde-fuerte">
                {{ __('Identifica ahora') }}
            </div>
            <div class="mt-1.5 max-w-[240px] text-[13px] leading-[1.4] opacity-85 text-verde-fuerte">
                {{ __('Sube o toma una foto y deja que la IA te ayude.') }}
            </div>
            <div class="mt-4 h-11">
                <flux:button href="{{ route('reconocimiento.index') }}"
                 class="text-white! bg-verde-fuerte! text-sm! font-semibold rounded-xl shadow-none! border-none!" 
                icon="scan-search">Empezar a escanear</flux:button>
            </div>
        </div>

        {{-- How it works --}}
        <section class="pt-7">
            <div class="flex items-baseline justify-between">
                <h2 class="font-display font-semibold text-xl text-ink">
                    {{ __('Cómo comenzar') }}
                </h2>
                <span class="text-[12px] text-ink-3">{{ __('En solo 3 pasos') }}</span>
            </div>

            <div class="mt-3 flex flex-col gap-2.5">
                @php
                    $steps = [
                        ['n' => '01', 'title' => __('Toma una foto'), 'desc' => __('Centra el hongo bien iluminado dentro del marco.'), 'color' => '#80b67e', 'icon' => 'camera'],
                        ['n' => '02', 'title' => __('Análisis con IA'), 'desc' => __('Identifica especie, comestibilidad y enfermedades.'), 'color' => '#f9cb43', 'icon' => 'sparkle'],
                        ['n' => '03', 'title' => __('Tratamientos'), 'desc' => __('Recibe descripción y opciones de tratamiento.'), 'color' => '#368ab8', 'icon' => 'shield'],
                    ];
                @endphp

                @foreach ($steps as $s)
                    <div class="flex items-center gap-3.5 rounded-2xl border border-line-2 bg-card p-3.5">
                        <div
                            class="flex size-11 shrink-0 items-center justify-center rounded-xl"
                            style="background: {{ $s['color'] }}1f; color: {{ $s['color'] }};"
                        >
                            @switch($s['icon'])
                                @case('camera')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[22px]">
                                        <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z" />
                                        <circle cx="12" cy="13" r="3.5" />
                                    </svg>
                                    @break
                                @case('sparkle')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[22px]">
                                        <path d="M12 3l2 6 6 2-6 2-2 6-2-6-6-2 6-2 2-6z" />
                                    </svg>
                                    @break
                                @case('shield')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[22px]">
                                        <path d="M12 3l8 3v6c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V6l8-3z" />
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[14px] font-bold text-ink">{{ $s['title'] }}</span>
                            </div>
                            <div class="mt-0.5 text-[13px] leading-[1.4] text-ink-2">{{ $s['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Recent identifications (placeholder grid — design-only) --}}
        <section class="pt-7">
            <div class="flex items-baseline justify-between">
                <h2 class="font-display font-semibold text-xl text-ink">
                    {{ __('Identificaciones recientes') }}
                </h2>
                <a href="{{ route('reconocimiento.index') }}" wire:navigate class="text-[12px] font-bold text-olive">
                    {{ __('Ver todas') }}
                </a>
            </div>

            @php
                $recent = [
                    ['name' => 'Cantharellus cibarius', 'date' => '13-04-2026', 'com' => __('Rebozuelo'), 'status' => 'sano', 'tone' => ['#caa050', '#8a6a2a', '#e4b35a'], 'image' => 'https://upload.wikimedia.org/wikipedia/commons/a/aa/2007-07-14_Cantharellus_cibarius.jpg'],
                    ['name' => 'Boletus edulis',        'date' => '22-04-2026', 'com' => __('Boleto'),    'status' => 'sano', 'tone' => ['#8a6a4a', '#5a4030', '#a07a52'], 'image' => 'https://upload.wikimedia.org/wikipedia/commons/b/b0/Boletus_edulis_EtgHollande_041031_091.jpg'],
                    ['name' => 'Agaricus bisporus',     'date' => '05-05-2026', 'com' => __('Champiñón blanco'),     'status' => 'plaga','tone' => ['#9a9080', '#6a6055', '#b8a890'], 'image' => 'https://extension.psu.edu/media/wysiwyg//extensions/catalog_product/9452fdf2a9104600895c1de77aa2f669/m/u/mushroom-infected-with-verticillium-dry-bubble-showing-split-stem-symptom.jpg'],
                    ['name' => 'Morchella esculenta',   'date' => '10-05-2026', 'com' => __('Colmenilla'),'status' => 'sano', 'tone' => ['#6a5238', '#3a2a1a', '#7a5a3a'], 'image' => 'https://www.amanitacesarea.com/imagenes/morchella/esculenta1.jpg'],
                ];
            @endphp

            <div class="mt-3 flex flex-col gap-5">
                @foreach ($recent as $m)
                    <div class="overflow-hidden rounded-2xl border border-line-2 bg-card px-9 pt-8 pb-4">
                        
                        <div class="relative rounded-2xl ">
                                <img class="relative z-0 block w-full rounded-2xl!" src="{{ $m['image'] }}" width="100%"  alt="{{ $m['name'] }}">
                            @if ($m['status'] === 'sano')
                                    <span class="absolute right-3 top-3 z-20">
                                        <flux:badge class="relative overflow-visible !bg-verde-claro !text-[#016630]">
                                        {{ __('Sano') }}
                                        <span class="pointer-events-none absolute -right-1 -top-1 flex size-2.5">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex size-2.5 rounded-full bg-green-500"></span>
                                        </span>
                                    </flux:badge>
                                </span>
                            @else
                                <span class="absolute right-3 top-3 z-20">
                                    <flux:badge class="relative overflow-visible bg-[#ffe0e1]! text-[#c20006]! ">
                                        {{ __('Plaga detectada') }}
                                        <span class="pointer-events-none absolute -right-1 -top-1 flex size-2.5">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex size-2.5 rounded-full bg-red-500"></span>
                                        </span>
                                    </flux:badge>
                                </span>
                            @endif
                        </div>

                        <div class="mt-5 p-2.5 flex flex-col items-start justify-center gap-2.5 text-ink">
                            
                            <div class="flex justify-start items-center gap-3 truncate font-display text-sm font-normal leading-[1.2] max-[540px]:flex-col max-[540px]:items-start max-[540px]:gap-1.5 max-[540px]:!whitespace-normal max-[540px]:!overflow-visible max-[540px]:!text-clip">
                               <span class="inline-flex gap-1.5 items-center">
                                    <flux:icon.book-marked class="size=3"/>
                                    Nombre comun:  
                                </span>  
                               <span class="italic font-medium ">
                                    <flux:badge color="yellow">
                                    {{ $m['com'] }}
                                    </flux:badge>
                                </span>
                            </div>
                            
                            <div class="flex justify-start items-center gap-3 truncate font-display text-sm font-normal leading-[1.2] max-[540px]:flex-col max-[540px]:items-start max-[540px]:gap-1.5 max-[540px]:!whitespace-normal max-[540px]:!overflow-visible max-[540px]:!text-clip">
                               <span class="inline-flex gap-1.5 items-center">
                                    <flux:icon.flask-conical class="size=3"/>
                                    Nombre cientifico:  
                                </span>  
                               <span class="italic font-medium ">
                                    <flux:badge color="lime">
                                    {{ $m['name'] }}
                                    </flux:badge>
                                </span>
                            </div>

                            @if ($m['status'] !== 'sano')
                                <div class="flex justify-start items-center gap-3 truncate font-display text-sm font-normal leading-[1.2] max-[540px]:flex-col max-[540px]:items-start max-[540px]:gap-1.5 max-[540px]:!whitespace-normal max-[540px]:!overflow-visible max-[540px]:!text-clip">
                                   <span class="inline-flex gap-1.5 items-center">
                                        <flux:icon.activity class="size=3"/>
                                        Nombre común de la enfermedad:  
                                    </span>  
                                   <span class="italic font-medium ">
                                        <flux:badge color="red">
                                        Verticillium
                                        </flux:badge>
                                    </span>
                                </div>

                                <div class="flex justify-start items-center gap-3 truncate font-display text-sm font-normal leading-[1.2] max-[540px]:flex-col max-[540px]:items-start max-[540px]:gap-1.5 max-[540px]:!whitespace-normal max-[540px]:!overflow-visible max-[540px]:!text-clip">
                                   <span class="inline-flex gap-1.5 items-center">
                                        <flux:icon.stethoscope class="size=3"/>
                                        Nombre científico de la enfermedad :  
                                    </span>  
                                   <span class="italic font-medium ">
                                        <flux:badge color="red">
                                        Burbuja Seca
                                        </flux:badge>
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-start items-center gap-3 truncate font-display text-sm font-normal leading-[1.2] max-[540px]:flex-col max-[540px]:items-start max-[540px]:gap-1.5 max-[540px]:!whitespace-normal max-[540px]:!overflow-visible max-[540px]:!text-clip">
                               <span class="inline-flex gap-1.5 items-center">
                                    <flux:icon.calendar-fold class="size=3"/>
                                    Fecha de análisis:  
                                </span>  
                               <span class="italic font-medium ">
                                    <flux:badge color="zinc">
                                    {{ $m['date'] }}
                                    </flux:badge>
                                </span>
                            </div>


                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts::app>
