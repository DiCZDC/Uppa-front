<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Services\UppaApiService;

new class extends Component
{
    use WithFileUploads;

    #[Validate('required|file|mimes:jpg,jpeg,png,webp|max:10240')]
    public $foto;

    public $resultado = null;

    public function analizar(UppaApiService $uppaApiService)
    {
        $this->validate();

        $path = $this->foto->getRealPath();

        $this->resultado = $uppaApiService->catalogarHongo($path);
    }
};