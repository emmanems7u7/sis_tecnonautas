<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250619 extends Seeder
{
    public function run()
    {
        $permisos = [
['id' => '177', 'name' => 'Horarios de profesores', 'tipo' => 'menu','id_relacion' => '30', 'guard_name' => 'web' ],
['id' => '176', 'name' => 'usuarios.reestablecer_contraseña', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '175', 'name' => 'Ver Estudiantes', 'tipo' => 'menu','id_relacion' => '29', 'guard_name' => 'web' ],
['id' => '174', 'name' => 'usuarios.ver_estudiantes', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '173', 'name' => 'tarea.revisar_estudiantes', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '172', 'name' => 'tarea.revisar', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '171', 'name' => 'tarea.crear', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '170', 'name' => 'evaluacion.crear', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '169', 'name' => 'contenido.tema_crear', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '168', 'name' => 'contenido._tema_crear', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '167', 'name' => 'paralelos.crear_gestion', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '166', 'name' => 'paralelos.eliminar_gestion', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '165', 'name' => 'paralelos.editar_gestion', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
['id' => '164', 'name' => 'paralelos.gestion', 'tipo' => 'permiso','id_relacion' => '', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}