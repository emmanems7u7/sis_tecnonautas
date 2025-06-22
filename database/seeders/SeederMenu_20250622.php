<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class SeederMenu_20250622 extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'id' => '32',
                'nombre' => 'Ver Emails',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 21,
                'ruta' => 'mails.filter.form.index',
                'accion_usuario' => '',
            ],
            [
                'id' => '31',
                'nombre' => 'Ver Email',
                'orden' => 2,
                'padre_id' => null,
                'seccion_id' => 21,
                'ruta' => 'mails.filter.form.index',
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