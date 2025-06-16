<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250604 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => '121', 'name' => 'Administración de Pagos', 'tipo' => 'seccion', 'guard_name' => 'web', 'id_relacion' => 17,],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}