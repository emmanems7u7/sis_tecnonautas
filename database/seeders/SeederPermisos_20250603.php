<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250603 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => 120, 'name' => 'modulos.guardar', 'tipo' => 'permiso', 'guard_name' => 'web'],
            ['id' => 119, 'name' => 'modulos.crear', 'tipo' => 'permiso', 'guard_name' => 'web'],
            ['id' => 118, 'name' => 'modulos.eliminar', 'tipo' => 'permiso', 'guard_name' => 'web'],
            ['id' => 117, 'name' => 'modulos.actualizar', 'tipo' => 'permiso', 'guard_name' => 'web'],
            ['id' => 116, 'name' => 'modulos.editar', 'tipo' => 'permiso', 'guard_name' => 'web'],
            ['id' => 115, 'name' => 'modulos.ver', 'tipo' => 'permiso', 'guard_name' => 'web'],

            ['id' => 92, 'name' => 'Materias y Modulos', 'tipo' => 'seccion', 'id_relacion' => 13, 'guard_name' => 'web'],
            ['id' => 93, 'name' => 'Materias', 'tipo' => 'menu', 'id_relacion' => 17, 'guard_name' => 'web'],
            ['id' => 95, 'name' => 'asignacion.ver', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 96, 'name' => 'asignacion.crear', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 97, 'name' => 'asignacion.editar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 98, 'name' => 'asignacion.actualizar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 99, 'name' => 'asignacion.guardar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 100, 'name' => 'asignacion.ver_materias', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 101, 'name' => 'asignacion.eliminar', 'tipo' => 'permiso', 'id_relacion' => null, 'guard_name' => 'web'],
            ['id' => 102, 'name' => 'inscripciones', 'tipo' => 'seccion', 'id_relacion' => 14, 'guard_name' => 'web'],
            ['id' => 103, 'name' => 'Inscribirme en una materia', 'tipo' => 'menu', 'id_relacion' => 18, 'guard_name' => 'web'],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}