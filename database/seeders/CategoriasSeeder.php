<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->toDateTimeString();

        DB::table('categorias')->insert([
            [
                'id' => 1,
                'nombre' => 'Departamentos',
                'descripcion' => 'Corresponde a registros de departamentos',
                'estado' => 1,
                'created_at' => '2025-05-02 19:01:23',
                'updated_at' => '2025-05-02 19:01:23',
            ],
            [
                'id' => 2,
                'nombre' => 'Ciudades',
                'descripcion' => 'Corresponde a registros de ciudades',
                'estado' => 1,
                'created_at' => '2025-05-02 19:08:38',
                'updated_at' => '2025-05-02 19:08:38',
            ],
            [
                'id' => 3,
                'nombre' => 'prueba',
                'descripcion' => 'asdf',
                'estado' => 1,
                'created_at' => '2025-05-02 19:12:22',
                'updated_at' => '2025-05-02 19:12:22',
            ],
            [
                'id' => 4,
                'nombre' => 'adsf',
                'descripcion' => 'adsf',
                'estado' => 1,
                'created_at' => '2025-05-02 19:12:25',
                'updated_at' => '2025-05-02 19:12:25',
            ],
            [
                'id' => 5,
                'nombre' => 'adsf',
                'descripcion' => 'adf',
                'estado' => 0,
                'created_at' => '2025-05-02 19:12:30',
                'updated_at' => '2025-05-02 19:12:30',
            ],
            [
                'id' => 6,
                'nombre' => 'adf',
                'descripcion' => 'adf',
                'estado' => 1,
                'created_at' => '2025-05-02 19:12:35',
                'updated_at' => '2025-05-02 19:12:35',
            ],
        ]);
    }
}
