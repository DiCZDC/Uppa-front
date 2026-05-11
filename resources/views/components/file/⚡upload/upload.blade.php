<div wire:loading.class.remove="ms-loading" class="space-y-5" x-data="micoscanCamera">
    {{-- ─── Camera / capture state ─────────────────────────────────────── --}}
    @if (is_null($resultado))
        <form
            wire:submit="analizar"
            class="space-y-4"
            wire:loading.class="opacity-0 pointer-events-none"
            wire:target="analizar"
        >
            <div
                class="relative aspect-[4/5] w-full max-w-md overflow-hidden rounded-3xl border border-white/10 text-cream"
                style="background: radial-gradient(80% 60% at 50% 60%, #4a5a32 0%, #2a341e 60%, #0e1408 100%);"
            >
                {{-- Texture --}}
                <div class="pointer-events-none absolute inset-0" style="background: repeating-linear-gradient(45deg, rgba(255,255,255,0.025) 0 2px, transparent 2px 8px);"></div>

                {{-- Live camera feed --}}
                <video
                    x-ref="video"
                    x-show="mode === 'camera'"
                    autoplay
                    playsinline
                    muted
                    class="absolute inset-0 size-full object-cover"
                ></video>

                {{-- Captured / selected photo preview --}}
                @if ($foto && method_exists($foto, 'temporaryUrl'))
                    <img
                        x-show="mode !== 'camera'"
                        src="{{ $foto->temporaryUrl() }}"
                        alt="{{ __('Vista previa') }}"
                        class="absolute inset-0 size-full object-cover"
                    />
                    <div x-show="mode !== 'camera'" class="pointer-events-none absolute inset-0 bg-grape/30"></div>
                @endif

                {{-- Reticle (always visible) --}}
                <div class="pointer-events-none absolute left-1/2 top-1/2 size-[260px] -translate-x-1/2 -translate-y-1/2">
                    <div class="absolute left-0 top-0 size-8 rounded-tl-2xl border-l-[3px] border-t-[3px] border-white"></div>
                    <div class="absolute right-0 top-0 size-8 rounded-tr-2xl border-r-[3px] border-t-[3px] border-white"></div>
                    <div class="absolute left-0 bottom-0 size-8 rounded-bl-2xl border-l-[3px] border-b-[3px] border-white"></div>
                    <div class="absolute right-0 bottom-0 size-8 rounded-br-2xl border-r-[3px] border-b-[3px] border-white"></div>
                </div>

                {{-- Status badge (top) --}}
                <div class="absolute left-0 right-0 top-4 flex justify-center">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-black/45 px-3 py-1.5 text-[12px] font-semibold backdrop-blur">
                        <span class="size-1.5 rounded-full bg-olive"></span>
                        <span x-show="mode === 'idle'">{{ $foto ? __('Foto lista') : __('Listo para escanear') }}</span>
                        <span x-show="mode === 'camera'" x-cloak>{{ __('Apunta y captura') }}</span>
                        <span x-show="mode === 'uploading'" x-cloak>{{ __('Subiendo foto…') }}</span>
                    </span>
                </div>

                {{-- IDLE controls — dos opciones equivalentes --}}
                <div
                    x-show="mode === 'idle'"
                    class="absolute inset-x-0 bottom-0 flex flex-col items-center gap-3 px-5 pb-7"
                >
                    <div class="grid w-full max-w-xs grid-cols-2 gap-2.5">
                        <button
                            type="button"
                            @click="openCamera()"
                            class="inline-flex h-[50px] items-center justify-center gap-1.5 rounded-2xl bg-gold px-3 text-[13px] font-bold tracking-[-0.1px] text-grape transition hover:brightness-105"
                            style="box-shadow: 0 6px 14px rgba(249,203,67,0.45);"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                                <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z" />
                                <circle cx="12" cy="13" r="3.5" />
                            </svg>
                            {{ $foto ? __('Volver a tomar') : __('Tomar foto') }}
                        </button>
                        <label
                            for="micoscan-foto"
                            class="inline-flex h-[50px] cursor-pointer items-center justify-center gap-1.5 rounded-2xl border border-cream/30 bg-black/35 px-3 text-[13px] font-bold tracking-[-0.1px] text-cream backdrop-blur transition hover:bg-black/50"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <circle cx="9" cy="10" r="1.5" />
                                <path d="M3 17l5-5 4 4 3-3 6 6" />
                            </svg>
                            {{ $foto ? __('Cambiar') : __('Elegir archivo') }}
                        </label>
                    </div>
                    @if (! $foto)
                        <p class="text-center text-[11px] text-cream/55">
                            {{ __('Toma una foto con la cámara o sube una imagen del hongo') }}
                        </p>
                    @endif
                </div>

                {{-- CAMERA controls --}}
                <div
                    x-show="mode === 'camera'"
                    x-cloak
                    class="absolute inset-x-0 bottom-0 flex items-center justify-between px-7 pb-7"
                >
                    <button
                        type="button"
                        @click="closeCamera()"
                        class="inline-flex size-12 items-center justify-center rounded-full border border-white/20 bg-black/45 text-white backdrop-blur transition hover:bg-black/65"
                        aria-label="{{ __('Cerrar cámara') }}"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        @click="capture()"
                        class="flex size-[78px] items-center justify-center rounded-full border-4 border-white p-1 transition active:scale-95"
                        aria-label="{{ __('Capturar foto') }}"
                    >
                        <span class="flex size-full items-center justify-center rounded-full bg-olive text-white" style="box-shadow: 0 0 24px rgba(128,182,126,0.5);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="size-7">
                                <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z" />
                                <circle cx="12" cy="13" r="3.5" />
                            </svg>
                        </span>
                    </button>
                    <span class="size-12"></span>
                </div>

                {{-- UPLOADING overlay --}}
                <div
                    x-show="mode === 'uploading'"
                    x-cloak
                    class="absolute inset-0 flex items-center justify-center bg-grape/55 backdrop-blur-sm"
                >
                    <div class="flex flex-col items-center gap-3 text-cream">
                        <div class="ms-spin size-10 rounded-full border-[3px] border-gold border-t-transparent"></div>
                        <div class="text-[13px] font-medium">{{ __('Procesando captura…') }}</div>
                    </div>
                </div>

                {{-- Hidden file input (gallery fallback) --}}
                <input
                    id="micoscan-foto"
                    type="file"
                    wire:model="foto"
                    accept="image/*"
                    capture="environment"
                    class="sr-only"
                />
            </div>

            {{-- Camera error (Alpine-side) --}}
            <div x-show="cameraError" x-cloak class="flex items-start gap-3 rounded-2xl border border-pink/30 bg-card p-4 text-pink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 shrink-0">
                    <path d="M12 3l10 17H2L12 3z" />
                    <path d="M12 10v5M12 18v.5" />
                </svg>
                <div class="text-[13px]" x-text="cameraError"></div>
            </div>

            @error('foto')
                <div class="flex items-start gap-3 rounded-2xl border border-pink/30 bg-card p-4 text-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 shrink-0">
                        <path d="M12 3l10 17H2L12 3z" />
                        <path d="M12 10v5M12 18v.5" />
                    </svg>
                    <div class="text-[13px]">{{ $message }}</div>
                </div>
            @enderror

            @if (session()->has('error'))
                <div class="flex items-start gap-3 rounded-2xl border border-pink/30 bg-card p-4 text-pink">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 shrink-0">
                        <path d="M12 3l10 17H2L12 3z" />
                        <path d="M12 10v5M12 18v.5" />
                    </svg>
                    <div class="text-[13px]">{{ session('error') }}</div>
                </div>
            @endif

            <div class="flex flex-col gap-2.5 sm:flex-row">
                <button
                    type="submit"
                    x-bind:disabled="mode !== 'idle' || !{{ $foto ? 'true' : 'false' }}"
                    @disabled(!$foto)
                    class="inline-flex h-[50px] flex-1 items-center justify-center gap-2 rounded-2xl bg-olive px-5 text-[15px] font-semibold text-white ms-shadow-cta transition hover:brightness-105 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                        <path d="M12 3l2 6 6 2-6 2-2 6-2-6-6-2 6-2 2-6z" />
                    </svg>
                    {{ __('Analizar foto') }}
                </button>
            </div>
        </form>
    @endif

    {{-- ─── Analyzing state ────────────────────────────────────────────── --}}
    <div
        wire:loading.flex
        wire:target="analizar"
        class="relative hidden flex-col gap-6 overflow-hidden rounded-3xl p-7 text-cream"
        style="background: radial-gradient(120% 80% at 50% 30%, #4a3f56 0%, #3c3546 60%, #1d1828 100%);"
    >
        <div class="relative">
            <div class="aspect-[4/3] w-full overflow-hidden rounded-2xl" style="background: linear-gradient(135deg, #5a6a3f 0%, #3e4a2c 100%);">
                <div class="absolute inset-0" style="background: repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 8px);"></div>
            </div>
            <div
                class="ms-scan-line pointer-events-none absolute left-0 right-0 top-0 h-0.5"
                style="background: linear-gradient(90deg, transparent, #f9cb43, transparent); box-shadow: 0 0 16px #f9cb43, 0 0 32px rgba(249,203,67,0.5);"
            ></div>
        </div>

        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-gold/20 px-3 py-1.5 text-[11px] font-bold tracking-[1px] text-gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3">
                    <path d="M12 3l2 6 6 2-6 2-2 6-2-6-6-2 6-2 2-6z" />
                </svg>
                {{ __('Analizando') }}
            </span>
            <h2 class="mt-3.5 font-display text-[26px] font-medium leading-[1.15] tracking-[-0.5px]">
                {{ __('Identificando especie') }}<br>{{ __('y signos de plaga…') }}
            </h2>
        </div>

        <div class="flex flex-col gap-2.5">
            @foreach ([
                ['label' => __('Procesando imagen'), 'state' => 'done'],
                ['label' => __('Comparando con base de datos'), 'state' => 'done'],
                ['label' => __('Detectando enfermedades'), 'state' => 'loading'],
                ['label' => __('Generando recomendaciones'), 'state' => 'pending'],
            ] as $step)
                <div class="flex items-center gap-2.5">
                    @if ($step['state'] === 'done')
                        <div class="flex size-5 items-center justify-center rounded-full bg-olive text-white">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="size-3">
                                <path d="M5 12l4 4L19 6" />
                            </svg>
                        </div>
                        <span class="text-[14px] font-medium text-cream">{{ $step['label'] }}</span>
                    @elseif ($step['state'] === 'loading')
                        <div class="ms-spin size-5 rounded-full border-2 border-gold border-t-transparent"></div>
                        <span class="text-[14px] font-bold text-gold">{{ $step['label'] }}</span>
                    @else
                        <div class="size-5 rounded-full bg-white/10"></div>
                        <span class="text-[14px] text-cream/45">{{ $step['label'] }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ─── Result state ───────────────────────────────────────────────── --}}
    @if (! is_null($resultado))
        @php
            $payload = is_array($resultado) || is_object($resultado) ? (array) $resultado : null;

            $first = function (array $haystack, array $keys, $default = null) {
                foreach ($keys as $k) {
                    if (array_key_exists($k, $haystack) && $haystack[$k] !== null && $haystack[$k] !== '') {
                        return $haystack[$k];
                    }
                }
                return $default;
            };

            $species   = $payload ? $first($payload, ['especie', 'species', 'nombre_cientifico', 'scientific_name', 'nombre']) : null;
            $common    = $payload ? $first($payload, ['nombre_comun', 'common_name', 'comun']) : null;
            $edible    = $payload ? $first($payload, ['comestible', 'edible']) : null;
            $confidence = $payload ? $first($payload, ['confianza', 'confidence', 'score']) : null;
            $description = $payload ? $first($payload, ['descripcion', 'description', 'detalle']) : null;
            $pest      = $payload ? $first($payload, ['plaga', 'pest', 'enfermedad', 'disease']) : null;
            $treatments = $payload ? $first($payload, ['tratamientos', 'treatments', 'tratamiento'], []) : [];

            if (is_string($treatments)) {
                $treatments = [$treatments];
            } elseif (! is_array($treatments)) {
                $treatments = [];
            }

            $confidencePct = is_numeric($confidence)
                ? ($confidence > 1 ? min(100, (float) $confidence) : (float) $confidence * 100)
                : null;
        @endphp

        <article class="overflow-hidden rounded-3xl border border-line-2 bg-card ms-shadow-card">
            {{-- Hero --}}
            <div class="relative h-56" style="background: linear-gradient(135deg, #9a9080 0%, #6a6055 100%);">
                <div class="absolute inset-0" style="background: repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0 2px, transparent 2px 8px);"></div>
                <img
                        x-show="mode !== 'camera'"
                        src="{{ $foto->temporaryUrl() }}"
                        alt="{{ __('Vista previa') }}"
                        class="absolute inset-0 size-full object-cover"
                    />
                <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, transparent 30%, transparent 60%);"></div>

                <div class="absolute left-5 -bottom-3.5 flex flex-wrap gap-2">
                    @if ($edible !== null)
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[12px] font-bold text-white {{ $edible ? 'bg-olive' : 'bg-pink' }}"
                            style="box-shadow: 0 6px 14px {{ $edible ? 'rgba(128,182,126,0.5)' : 'rgba(212,86,86,0.5)' }};"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                                @if ($edible)
                                    <path d="M5 12l4 4L19 6" />
                                @else
                                    <path d="M12 3l10 17H2L12 3z" /><path d="M12 10v5M12 18v.5" />
                                @endif
                            </svg>
                            {{ $edible ? __('Comestible') : __('No comestible') }}
                        </span>
                    @endif
                    @if ($pest)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-pink px-3 py-1.5 text-[12px] font-bold text-white" style="box-shadow: 0 6px 14px rgba(212,86,86,0.5);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                                <path d="M12 3l10 17H2L12 3z" /><path d="M12 10v5M12 18v.5" />
                            </svg>
                            {{ __('Plaga detectada') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Title --}}
            <div class="px-5 pt-9 pb-2">
                <div class="text-[12px] font-bold tracking-[1.2px] text-ink-3 uppercase">
                    {{ __('Hongo identificado') }}
                </div>
                @if ($species)
                    <h2 class="mt-1.5 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px] text-ink">{{ $species }}</h2>
                @endif
                @if ($common)
                    <div class="text-[14px] text-ink-2">{{ $common }}</div>
                @endif
            </div>

            {{-- Confidence --}}
            @if ($confidencePct !== null)
                <div class="mx-5 mt-4 rounded-2xl border border-line-2 bg-card p-3.5">
                    <div class="flex items-baseline justify-between">
                        <span class="text-[12px] font-semibold text-ink-2">{{ __('Confianza del modelo') }}</span>
                        <span class="font-display text-[18px] font-semibold text-olive">{{ number_format($confidencePct, 0) }}%</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-line-2">
                        <div
                            class="h-full rounded-full"
                            style="width: {{ $confidencePct }}%; background: linear-gradient(90deg, #80b67e, #f9cb43);"
                        ></div>
                    </div>
                </div>
            @endif

            {{-- Description --}}
            @if ($description)
                <div class="mx-5 mt-4 rounded-2xl border border-line-2 bg-card p-4">
                    <div class="font-display text-[16px] font-medium tracking-[-0.2px] text-ink">{{ __('Sobre este hongo') }}</div>
                    <p class="mt-1.5 text-[13px] leading-[1.55] text-ink-2">{{ $description }}</p>
                </div>
            @endif

            {{-- Pest --}}
            @if ($pest)
                <div class="mx-5 mt-4 rounded-2xl border border-pink/30 bg-card p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-pink/10 text-pink">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="size-[22px]">
                                <path d="M12 3l10 17H2L12 3z" />
                                <path d="M12 10v5M12 18v.5" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-[11px] font-bold tracking-[0.8px] text-pink uppercase">{{ __('Plaga detectada') }}</div>
                            <div class="font-display text-[17px] font-medium tracking-[-0.2px] text-ink">
                                {{ is_array($pest) ? ($pest['nombre'] ?? $pest['name'] ?? json_encode($pest, JSON_UNESCAPED_UNICODE)) : $pest }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Treatments --}}
            @if (! empty($treatments))
                <div class="mx-5 mt-4">
                    <div class="flex items-baseline justify-between">
                        <h3 class="font-display text-[18px] font-medium tracking-[-0.3px] text-ink">{{ __('Tratamientos sugeridos') }}</h3>
                        <span class="text-[12px] text-ink-3">{{ count($treatments) }} {{ count($treatments) === 1 ? __('opción') : __('opciones') }}</span>
                    </div>
                    <div class="mt-2.5 flex flex-col gap-2.5">
                        @foreach ($treatments as $i => $t)
                            @php
                                $title = is_array($t) ? ($t['titulo'] ?? $t['title'] ?? null) : null;
                                $desc = is_array($t) ? ($t['descripcion'] ?? $t['description'] ?? null) : (string) $t;
                                $palette = ['#80b67e', '#368ab8', '#f9cb43'][$i % 3];
                            @endphp
                            <div class="flex gap-3 rounded-2xl border border-line-2 bg-card p-3.5">
                                <div
                                    class="flex size-9 shrink-0 items-center justify-center rounded-xl"
                                    style="background: {{ $palette }}1a; color: {{ $palette }};"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                                        <path d="M12 3c4 5 6 8 6 11a6 6 0 11-12 0c0-3 2-6 6-11z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    @if ($title)
                                        <div class="text-[14px] font-bold text-ink">{{ $title }}</div>
                                    @endif
                                    @if ($desc)
                                        <div class="mt-0.5 text-[13px] leading-[1.4] text-ink-2">{{ $desc }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Raw payload fallback --}}
            @if (! $species && ! $common && ! $description && ! $pest && empty($treatments))
                <div class="mx-5 mt-4 overflow-hidden rounded-2xl border border-line-2 bg-bg p-4">
                    <div class="text-[11px] font-bold tracking-[0.8px] text-ink-3 uppercase mb-2">
                        {{ __('Respuesta del modelo') }}
                    </div>
                    <pre class="overflow-x-auto whitespace-pre-wrap break-words font-mono text-[12px] leading-[1.5] text-ink-2">{{ is_array($resultado) || is_object($resultado) ? json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $resultado }}</pre>
                </div>
            @endif

            {{-- CTAs --}}
            <div class="flex flex-col gap-2.5 p-5 pt-6 sm:flex-row">
                <button
                    type="button"
                    wire:click="$set('resultado', null)"
                    class="inline-flex h-[50px] flex-1 items-center justify-center gap-2 rounded-2xl bg-olive px-5 text-[15px] font-semibold text-white ms-shadow-cta transition hover:brightness-105"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-[18px]">
                        <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z" />
                        <circle cx="12" cy="13" r="3.5" />
                    </svg>
                    {{ __('Nuevo escaneo') }}
                </button>
            </div>
        </article>
    @endif
</div>

@script
<script>
    Alpine.data('micoscanCamera', () => ({
        mode: 'idle',          // 'idle' | 'camera' | 'uploading'
        stream: null,
        cameraError: null,

        init() {
            this._beforeUnload = () => this.closeCamera();
            window.addEventListener('beforeunload', this._beforeUnload);
        },

        destroy() {
            window.removeEventListener('beforeunload', this._beforeUnload);
            this.closeCamera();
        },

        async openCamera() {
            this.cameraError = null;

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.cameraError = 'Tu navegador no soporta acceso a la cámara.';
                return;
            }

            if (!window.isSecureContext) {
                this.cameraError = 'La cámara solo funciona sobre HTTPS o en localhost.';
                return;
            }

            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false,
                });
                this.mode = 'camera';
                await this.$nextTick();
                const video = this.$refs.video;
                if (!video) return;
                video.srcObject = this.stream;
                try { await video.play(); } catch (_) { /* iOS Safari may need user gesture; click already counts */ }
            } catch (err) {
                let msg = 'No se pudo abrir la cámara.';
                if (err && err.name === 'NotAllowedError') {
                    msg = 'Permiso de cámara denegado. Habilítalo en los ajustes del navegador.';
                } else if (err && err.name === 'NotFoundError') {
                    msg = 'No se encontró ninguna cámara conectada.';
                } else if (err && err.name === 'NotReadableError') {
                    msg = 'La cámara está siendo usada por otra aplicación.';
                }
                this.cameraError = msg;
                this.mode = 'idle';
            }
        },

        closeCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(t => t.stop());
                this.stream = null;
            }
            const video = this.$refs.video;
            if (video) video.srcObject = null;
            if (this.mode === 'camera') this.mode = 'idle';
        },

        async capture() {
            const video = this.$refs.video;
            if (!video || !video.videoWidth) {
                this.cameraError = 'La cámara aún no está lista.';
                return;
            }

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.92));
            if (!blob) {
                this.cameraError = 'No se pudo capturar la foto.';
                return;
            }

            const file = new File([blob], 'micoscan-' + Date.now() + '.jpg', { type: 'image/jpeg' });

            this.closeCamera();
            this.mode = 'uploading';

            this.$wire.upload(
                'foto',
                file,
                () => { this.mode = 'idle'; },
                () => { this.cameraError = 'Error al subir la foto.'; this.mode = 'idle'; },
            );
        },
    }));
</script>
@endscript
