<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250605 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => '127', 'name' => 'Estudiante', 'tipo' => 'seccion', 'guard_name' => 'web' ],
            ['id' => '126', 'name' => 'Horarios de Profesor', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => '125', 'name' => 'Profesores', 'tipo' => 'seccion', 'guard_name' => 'web' ],
            ['id' => '124', 'name' => 'Apoderados', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => '123', 'name' => 'Informacion Personal', 'tipo' => 'seccion', 'guard_name' => 'web' ],
            ['id' => '122', 'name' => 'Pagos Pendientes', 'tipo' => 'menu', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}