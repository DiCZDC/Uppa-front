<div wire:loading.class.remove="ms-loading" class="space-y-5">
    {{-- ─── Capture state ─────────────────────────────────────── --}}
    @if (is_null($resultado))
        <div
            x-data="{
                mode: 'file',
                stream: null,
                facingMode: 'environment',

                async startCamera() {
                    this.mode = 'camera';
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: this.facingMode },
                            audio: false
                        });
                        await this.$nextTick();
                        this.$refs.video.srcObject = this.stream;
                    } catch (e) {
                        alert('No se pudo acceder a la cámara: ' + e.message);
                        this.mode = 'file';
                    }
                },

                stopCamera() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                        this.stream = null;
                    }
                    this.mode = 'file';
                },

                async flipCamera() {
                    this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                    this.stopCamera();
                    await this.startCamera();
                },

                async capture() {
                    const video  = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    canvas.width  = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);

                    canvas.toBlob(async (blob) => {
                        const file = new File([blob], 'captura.jpg', { type: 'image/jpeg' });
                        const dt   = new DataTransfer();
                        dt.items.add(file);
                        this.$refs.fileInput.files = dt.files;
                        this.$refs.fileInput.dispatchEvent(new Event('change'));
                        this.stopCamera();
                    }, 'image/jpeg', 0.92);
                }
            }"
            x-init="
                $watch('mode', v => { if (v !== 'camera' && stream) stopCamera(); })
            "
            @keydown.escape.window="if (mode === 'camera') stopCamera()"
            class="space-y-4"
        >

            {{-- ── Viewfinder / Preview ───────────────────────────────────── --}}
            <div class="relative aspect-[4/5] w-full max-w-md overflow-hidden rounded-3xl border border-white/10 text-cream bg-[url('https://cdn.pixabay.com/photo/2013/06/05/22/03/mushrooms-116973_1280.jpg')] bg-cover bg-center">

                {{-- Live camera feed --}}
                <video
                    x-ref="video"
                    x-show="mode === 'camera'"
                    autoplay
                    playsinline
                    muted
                    class="absolute inset-0 size-full object-cover"
                ></video>
                {{-- Hidden canvas used for frame capture --}}
                <canvas x-ref="canvas" class="hidden"></canvas>

                {{-- Reticle --}}
                <div class="pointer-events-none absolute left-1/2 top-1/2 size-[260px] -translate-x-1/2 -translate-y-1/2">
                    <div class="absolute left-0 top-0 size-8 rounded-tl-2xl border-l-[3px] border-t-[3px] border-white"></div>
                    <div class="absolute right-0 top-0 size-8 rounded-tr-2xl border-r-[3px] border-t-[3px] border-white"></div>
                    <div class="absolute left-0 bottom-0 size-8 rounded-bl-2xl border-l-[3px] border-b-[3px] border-white"></div>
                    <div class="absolute right-0 bottom-0 size-8 rounded-br-2xl border-r-[3px] border-b-[3px] border-white"></div>
                </div>

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

                {{-- Flip camera button (visible while camera is active) --}}
                <button
                    x-show="mode === 'camera'"
                    x-cloak
                    @click="flipCamera"
                    type="button"
                    class="absolute top-4 right-4 flex size-10 items-center justify-center rounded-full bg-black/50 text-white backdrop-blur-sm transition hover:bg-black/70"
                    title="{{ __('Cambiar cámara') }}"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                        <path d="M1 4v6h6" />
                        <path d="M23 20v-6h-6" />
                        <path d="M20.49 9A9 9 0 005.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 013.51 15" />
                    </svg>
                </button>

                {{-- Shutter button (visible while camera is active) --}}
                <button
                    x-show="mode === 'camera'"
                    x-cloak
                    @click="capture"
                    type="button"
                    class="absolute bottom-5 left-1/2 -translate-x-1/2 flex size-16 items-center justify-center rounded-full border-4 border-white bg-white/20 backdrop-blur-sm transition hover:bg-white/30 active:scale-95"
                    title="{{ __('Tomar foto') }}"
                >
                    <div class="size-10 rounded-full bg-white"></div>
                </button>
            </div>

            {{-- ── Form & controls ─────────────────────────────────────────── --}}
            <form
                wire:submit="analizar"
                class="space-y-4"
                wire:loading.class="opacity-0 pointer-events-none"
                wire:target="analizar"
            >
                <div class="flex flex-col gap-3 rounded-2xl bg-card p-4 border border-line-2">
                    {{-- Mode toggle --}}
                    <div class="flex gap-2">
                        
                        <button
                            type="button"
                            @click="startCamera"
                            :class="mode === 'camera' ? 'bg-olive text-white' : 'bg-olive/10 text-olive'"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl py-2 text-sm font-semibold transition">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                <path d="M23 7l-7 5 7 5V7z" />
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2" />
                            </svg>
                            {{ __('Usar cámara') }}
                        </button>
                    </div>

                    {{-- File input (always present for wire:model, hidden in camera mode) --}}
                    <div x-show="mode === 'file'">
                        <input
                            x-ref="fileInput"
                            type="file"
                            wire:model="foto"
                            accept="image/*"
                            class="text-sm text-ink-2 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-olive/10 file:text-olive hover:file:bg-olive/20 cursor-pointer"
                        >
                    </div>

                    {{-- Camera hint --}}
                    <p x-show="mode === 'camera'" x-cloak class="text-center text-[13px] text-ink-3">
                        {{ __('Encuadra el hongo y presiona el botón blanco para capturar') }}
                    </p>
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
                    <flux:button icon="scan" type="submit" variant="primary" class="w-full" :disabled="!$foto" 
                                class="w-full bg-verde-fuerte! text-white! hover:bg-olive/90! disabled:bg-verde-claro! disabled:text-verde-fuerte! border-none!
                                  disabled:cursor-not-allowed! transition!">
                                 Analizar foto
                    </flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- ─── Analyzing state ────────────────────────────────────────────── --}}
    <div
        wire:loading
        wire:target="analizar"
        class="w-full rounded-3xl bg-card p-7 border border-line-2 ms-shadow-card"
    >
        <div x-data="{ progress: 0 }" x-init="setInterval(() => { progress = progress >= 95 ? 95 : progress + (Math.random() * 10) }, 300)">
            <flux:field>
                <flux:label class="text-lg font-semibold">{{ __('Identificando especie...') }}</flux:label>
                <flux:progress ::value="progress" color="green" class="mt-2 mb-1" />
                <flux:description>{{ __('Procesando imagen y analizando características') }}</flux:description>
            </flux:field>
        </div>
    </div>

    {{-- ─── Result state ───────────────────────────────────────────────── --}}
    @if (! is_null($resultado))
        @php
            $payload = is_array($resultado) || is_object($resultado) ? (array) $resultado : null;

            $detectado = $payload['detectado'] ?? false;
            $especie   = $payload['especie'] ?? null;
            $confianza = $payload['confianza'] ?? null;
            $cultivada = $payload['cultivada'] ?? null;
            $mensaje   = $payload['mensaje'] ?? null;
            $id_especie = $payload['id_especie'] ?? null;

            $confidencePct = is_numeric($confianza)
                ? (($confianza <= 1 && $confianza > 0) ? (float) $confianza * 100 : (float) $confianza)
                : null;
        @endphp

        <article class="overflow-hidden rounded-3xl border border-line-2 bg-card ms-shadow-card">
            {{-- Hero --}}
            <div class="relative h-56 bg-olive/20">
                
                @if ($foto && method_exists($foto, 'temporaryUrl'))
                    <img
                        src="{{ $foto->temporaryUrl() }}"
                        alt="{{ __('Vista previa') }}"
                        class="absolute inset-0 size-full object-cover"
                    />
                @endif

                <div class="absolute left-5 -bottom-3.5 flex flex-wrap gap-2">
                    @if ($cultivada !== null)
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-[12px] font-bold text-white {{ $cultivada ? 'bg-olive' : 'bg-blue' }}"
                            style="box-shadow: 0 6px 14px {{ $cultivada ? 'rgba(128,182,126,0.5)' : 'rgba(54,138,184,0.5)' }};"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" class="size-3.5">
                                @if ($cultivada)
                                    <path d="M5 12l4 4L19 6" />
                                @else
                                    <path d="M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                                @endif
                            </svg>
                            {{ $cultivada ? __('Cultivable') : __('Silvestre') }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Title --}}
            <div class="px-5 pt-9 pb-2">
                <div class="text-[12px] font-bold tracking-[1.2px] text-ink-3 uppercase">
                    {{ $detectado ? __('Hongo identificado') : __('No se detectó un hongo') }}
                </div>
                @if ($especie)
                    <h2 class="mt-1.5 font-display text-[28px] font-medium leading-[1.1] tracking-[-0.6px] text-ink">{{ $especie }}</h2>
                @endif
            </div>

            {{-- Confidence --}}
            @if ($confidencePct !== null)
                <div class="mx-5 mt-4 rounded-2xl border border-line-2 bg-card p-3.5">
                    <div class="flex items-baseline justify-between">
                        <span class="text-base font-semibold text-ink-2">{{ __('Seguridad del resultado: ') }}
                            @switch(number_format($confidencePct, 0))
                                @case(number_format($confidencePct, 0) >= 80)
                                    <span class="text-olive font-bold">{{ __('Excelente') }}</span>
                                    
                                    @break
                                @case(number_format($confidencePct, 0) >= 60)
                                    <span class="text-blue font-bold" >{{ __('Buena') }}</span>
                                    @break
                                @case(number_format($confidencePct, 0) >= 50)
                                    <span class="text-amber-400 font-bold" >{{ __('Regular') }}</span>
                                    @break
                                @default
                                    <span class="text-red-700 font-bold">{{ __('Baja') }}</span>
                            @endswitch
                        </span>
                        <span class="font-display text-[18px] font-semibold 
                        @switch(number_format($confidencePct, 0))
                                @case(number_format($confidencePct, 0) >= 80)
                                    text-olive
                                    @break
                                @case(number_format($confidencePct, 0) >= 60)
                                    text-blue
                                    @break
                                @case(number_format($confidencePct, 0) >= 50)
                                    text-amber-400
                                    @break
                                @default
                                    text-red-700
                            @endswitch
                        ">{{ number_format($confidencePct, 0) }}%</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-line-2">
                        <div
                            class="h-full rounded-full"
                            style="width: {{ $confidencePct }}%; background: linear-gradient(90deg, #80b67e, #f9cb43);"
                        ></div>
                    </div>
                </div>
            @endif

            {{-- Message / Info --}}
            @if ($mensaje)
                <div class="mx-5 mt-4 rounded-2xl border border-line-2 bg-card p-4">
                    <div class="font-display text-[16px] font-medium tracking-[-0.2px] text-ink">{{ __('Información') }}</div>
                    <p class="mt-1.5 text-[13px] leading-[1.55] text-ink-2">{{ $mensaje }}</p>
                </div>
            @endif

            {{-- Raw payload fallback --}}
            @if (! $especie && ! $mensaje)
                <div class="mx-5 mt-4 overflow-hidden rounded-2xl border border-line-2 bg-bg p-4">
                    <div class="text-[11px] font-bold tracking-[0.8px] text-ink-3 uppercase mb-2">
                        {{ __('Respuesta del modelo') }}
                    </div>
                    <pre class="overflow-x-auto whitespace-pre-wrap break-words font-mono text-[12px] leading-[1.5] text-ink-2">{{ is_array($resultado) || is_object($resultado) ? json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $resultado }}</pre>
                </div>
            @endif

            {{-- CTAs --}}
            <div class="flex flex-col gap-2.5 p-5 pt-6 sm:flex-row">
                <flux:button wire:click="$set('resultado', null)" icon="scan-search" type="button" class="w-full" 
                                class="w-full bg-verde-claro! text-verde-fuerte! border-none!">
                                 Nuevo escaneo 
                </flux:button>
            </div>
        </article>
    @endif
</div>



