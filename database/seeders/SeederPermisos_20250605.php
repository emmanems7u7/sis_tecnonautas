<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250605 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => '127', 'name' => 'Estudiante', 'tipo' => 'seccion', 'id_relacion' => 20, 'guard_name' => 'web'],
            ['id' => '126', 'name' => 'Horarios de Profesor', 'tipo' => 'menu', 'id_relacion' => 24, 'guard_name' => 'web'],
            ['id' => '125', 'name' => 'Profesores', 'tipo' => 'seccion', 'id_relacion' => 19, 'guard_name' => 'web'],
            ['id' => '124', 'name' => 'Apoderados', 'tipo' => 'menu', 'id_relacion' => 23, 'guard_name' => 'web'],
            ['id' => '123', 'name' => 'Informacion Personal', 'tipo' => 'seccion', 'id_relacion' => 18, 'guard_name' => 'web'],
            ['id' => '122', 'name' => 'Pagos Pendientes', 'tipo' => 'menu', 'id_relacion' => 22, 'guard_name' => 'web'],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}