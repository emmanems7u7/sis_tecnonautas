<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250623 extends Seeder
{
    public function run()
    {
        $permisos = [
['id' => '180', 'name' => 'evaluacion.estudiantes', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '179', 'name' => 'evaluacion.crear_preguntas', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}