<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Services\UppaApiService;
new class extends Component
{
    use WithFileUploads;

    #[Validate('file', 'required|file|max:10240')]
    public $foto;
    public $resultado = null;
    public $cargando = false;

    public $datos =null;
    public function analizar(UppaApiService $uppaApiService)
    {
        $this->validate();
        $this->cargando = true;

        $path = $this->foto->getRealPath();

        $this->resultado = $uppaApiService->catalogarHongo($path);
    }
};
