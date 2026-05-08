<div>
    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
    <form wire:submit="analizar">
        <input type="file" wire:model="foto">
    
        @error('foto') <span class="error">{{ $message }}</span> @enderror
    
        <button type="submit">Save photo</button>
    </form>
    <div>
        {{ $resultado->nombre_comun ?? 'No se ha analizado ninguna imagen aún.' }}
    </div>
</div>