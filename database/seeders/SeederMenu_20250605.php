<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250605 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'nombre' => 'Horarios de Profesor',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 19,
                'ruta' => 'profesores.horarios.index',
                'accion_usuario' => '',
            ],
            [
                'nombre' => 'Apoderados',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 18,
                'ruta' => 'apoderados.index',
                'accion_usuario' => '',
            ],
            [
                'nombre' => 'Pagos Pendientes',
                'orden' => 1,
                'padre_id' => null,
                'seccion_id' => 17,
                'ruta' => 'Pago.pendiente.index',
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