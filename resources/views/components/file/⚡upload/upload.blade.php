<div>
    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
    <form wire:submit="analizar">
        <input type="file" wire:model="foto">
    
        @error('foto') <span class="error">{{ $message }}</span> @enderror
    
        <button type="submit">Save photo</button>
    </form>
    <div>
        @if (session()->has('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        @if (!is_null($resultado))
            <pre>{{ is_array($resultado) || is_object($resultado)
                ? json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                : $resultado }}</pre>
        @else
            <p>No se ha analizado ninguna imagen aún.</p>
        @endif
    </div>
</div>