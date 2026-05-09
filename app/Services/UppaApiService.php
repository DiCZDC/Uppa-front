<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UppaApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.uppa_api.url');
    }

    public function catalogarHongo(string $imagePath): array
    {
        $response = Http::attach(
            'file',
            file_get_contents($imagePath),
            basename($imagePath)
        )->post("{$this->baseUrl}/analizar");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error al contactar la API: ' . $response->status().' - ' . $response->body());
    }
}