<?php

namespace Database\Seeders;

use App\Models\Especie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EspecieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data/especies.csv');

        Schema::disableForeignKeyConstraints();
        Especie::truncate();
        Schema::enableForeignKeyConstraints();
        
        $file = fopen($path, 'r');
        $isHeader = true;
        
        while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            Especie::create([
                'id' => (int) $data[0],
                'nombre_cientifico' => $data[1],
                'nombre_comun' => $data[2] ?: null,
                'familia' => $data[3],
                'zonas_crecimiento' => $data[4] ?: null,
                'ambientes_comunes' => $data[5] ?: null,
            ]);
        }

        fclose($file);
    }
}
