<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250617 extends Seeder
{
    public function run()
    {
        $permisos = [
['id' => '163', 'name' => 'notas.exportar_excel', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '162', 'name' => 'notas.exportar_pdf', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}