<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250619 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '30',
                'nombre' => 'Horarios de profesores',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 19,
                'ruta' => 'horarios_profesores.index',
                'accion_usuario' => '',
            ],
            [
                'id' => '29',
                'nombre' => 'Ver Estudiantes',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 20,
                'ruta' => 'estudiantes.index',
                'accion_usuario' => '',
            ],
        ];

        foreach ($menus as $data) {
            Menu::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }
    }
}