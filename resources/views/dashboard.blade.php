<x-layouts::app :title="__('Inicio')">
    <div class="mx-auto w-full max-w-3xl px-5 pt-2 pb-12">
        {{-- Greeting --}}
        <div class="pb-3">
            <div class="text-[13px] text-ink-2">
                {{ __('Hola') }}, {{ auth()->user()->name }} 👋
            </div>
            <h1 class="mt-1 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px] text-ink">
                {{ __('¿Qué hongo') }}<br>{{ __('encontraste hoy?') }}
            </h1>
        </div>

        {{-- Hero scan card --}}
        <a
            href="{{ route('reconocimiento.index') }}"
            wire:navigate
            class="relative block overflow-hidden rounded-3xl p-6 text-white transition hover:brightness-105"
            style="background: linear-gradient(135deg, #80b67e 0%, #5e9a64 100%); box-shadow: 0 12px 28px rgba(128,182,126,0.40);"
        >
            <div class="pointer-events-none absolute -top-3 -right-5 opacity-[0.18]">
                <x-app-logo-icon mode="mono-light" class="size-[140px]" />
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-2.5 py-1 text-[11px] font-bold tracking-[0.4px]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-3">
                    <path d="M12 3l2 6 6 2-6 2-2 6-2-6-6-2 6-2 2-6z" />
                </svg>
                IA · 2 SEG
            </span>
            <div class="mt-3.5 max-w-[240px] font-display text-[24px] font-medium leading-[1.15] tracking-[-0.4px]">
                {{ __('Escanea y descubre la especie') }}
            </div>
            <div class="mt-1.5 max-w-[240px] text-[13px] leading-[1.4] opacity-85">
                {{ __('Sube una foto y deja que la IA haga el trabajo.') }}
            </div>
            <span class="mt-4 inline-flex h-11 items-center gap-2 rounded-xl bg-white px-4 text-[14px] font-bold text-olive">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                    <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z" />
                    <circle cx="12" cy="13" r="3.5" />
                </svg>
                {{ __('Empezar a escanear') }}
            </span>
        </a>

        {{-- How it works --}}
        <section class="pt-7">
            <div class="flex items-baseline justify-between">
                <h2 class="font-display text-[19px] font-medium tracking-[-0.3px] text-ink">
                    {{ __('Cómo funciona') }}
                </h2>
                <span class="text-[12px] text-ink-3">{{ __('3 pasos') }}</span>
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
                                <span class="text-[11px] font-bold tracking-[1px] text-ink-3">{{ $s['n'] }}</span>
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
                <h2 class="font-display text-[19px] font-medium tracking-[-0.3px] text-ink">
                    {{ __('Identificaciones recientes') }}
                </h2>
                <a href="{{ route('reconocimiento.index') }}" wire:navigate class="text-[12px] font-bold text-olive">
                    {{ __('Ver todas') }}
                </a>
            </div>

            @php
                $recent = [
                    ['name' => 'Cantharellus cibarius', 'com' => __('Rebozuelo'), 'status' => 'sano', 'tone' => ['#caa050', '#8a6a2a', '#e4b35a']],
                    ['name' => 'Boletus edulis',        'com' => __('Boleto'),    'status' => 'sano', 'tone' => ['#8a6a4a', '#5a4030', '#a07a52']],
                    ['name' => 'Pleurotus ostreatus',   'com' => __('Ostra'),     'status' => 'plaga','tone' => ['#9a9080', '#6a6055', '#b8a890']],
                    ['name' => 'Morchella esculenta',   'com' => __('Colmenilla'),'status' => 'sano', 'tone' => ['#6a5238', '#3a2a1a', '#7a5a3a']],
                ];
            @endphp

            <div class="mt-3 grid grid-cols-2 gap-2.5">
                @foreach ($recent as $m)
                    <div class="overflow-hidden rounded-2xl border border-line-2 bg-card">
                        <div
                            class="relative h-24"
                            style="background: linear-gradient(135deg, {{ $m['tone'][0] }} 0%, {{ $m['tone'][1] }} 100%);"
                        >
                            <div class="absolute inset-0" style="background: repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 8px);"></div>
                            <svg viewBox="0 0 120 120" preserveAspectRatio="xMidYMax meet" class="absolute inset-0 size-full opacity-75">
                                <ellipse cx="60" cy="60" rx="38" ry="22" fill="{{ $m['tone'][2] }}" />
                                <rect x="50" y="58" width="20" height="38" rx="6" fill="rgba(255,255,255,0.85)" />
                                <ellipse cx="60" cy="60" rx="38" ry="6" fill="rgba(0,0,0,0.18)" />
                            </svg>
                        </div>
                        <div class="p-2.5">
                            <div class="truncate font-display text-[13px] font-medium italic leading-[1.2] text-ink">
                                {{ $m['name'] }}
                            </div>
                            <div class="mt-0.5 text-[11px] text-ink-3">{{ $m['com'] }}</div>
                            <div class="mt-1.5 flex items-center gap-1.5">
                                <span class="size-1.5 rounded-full {{ $m['status'] === 'sano' ? 'bg-olive' : 'bg-pink' }}"></span>
                                <span class="text-[11px] font-semibold {{ $m['status'] === 'sano' ? 'text-olive' : 'text-pink' }}">
                                    {{ $m['status'] === 'sano' ? __('Sano') : __('Plaga detectada') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts::app>
