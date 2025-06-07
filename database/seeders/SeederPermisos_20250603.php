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
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}