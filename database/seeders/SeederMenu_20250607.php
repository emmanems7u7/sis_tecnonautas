<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250607 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => 25,
                'nombre' => 'Credenciales',
                'orden' => 4,
                'padre_id' => null,
                'seccion_id' => 6,
                'ruta' => 'configuracion.credenciales.index',
                'accion_usuario' => '',
            ],
            [
                'id' => 26,
                'nombre' => 'Pagos Estudiantes',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 17,
                'ruta' => 'pagos.index',
                'accion_usuario' => '',
            ],
            [
                'id' => 27,
                'nombre' => 'Ver Email',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 21,
                'ruta' => 'emails.index',
                'accion_usuario' => '',
            ],
            [
                'id' => 28,
                'nombre' => 'Inscribir estudiante',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 10,
                'ruta' => 'inscripcion.index',
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