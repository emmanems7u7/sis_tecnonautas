<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250622 extends Seeder
{
    public function run()
    {
        $permisos = [
['id' => '178', 'name' => 'Ver Emails', 'tipo' => 'menu','id_relacion' => '32', 'guard_name' => 'web' ],
['id' => '131', 'name' => 'Ver Email', 'tipo' => 'menu','id_relacion' => '31', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}