<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250607 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => '157', 'name' => 'estudiante.cambiar_estado', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '156', 'name' => 'estudiante.pagos_materias', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '155', 'name' => 'estudiante.ver_detalle', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '154', 'name' => 'estudiante.ver_inactivos', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '153', 'name' => 'estudiante.asignar_paralelo', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '152', 'name' => 'asistencia.editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '151', 'name' => 'asistencia.ver_detalle', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '150', 'name' => 'asistencia.generar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '149', 'name' => 'modulos.modulos.temas_eliminar_contenido', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '148', 'name' => 'modulos.modulos.temas_obtener', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '147', 'name' => 'modulos.modulos.temas_guardar_contenido', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '146', 'name' => 'modulos.modulos.temas_guardar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '145', 'name' => 'modulos.modulos.temas_crear', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '144', 'name' => 'modulos.modulos.temas_eliminar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '143', 'name' => 'modulos.modulos.temas_actualizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '142', 'name' => 'modulos.modulos.temas_editar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '141', 'name' => 'modulos.modulos.temas_finalizar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '140', 'name' => 'modulos.modulos.temas_detalles', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '139', 'name' => 'modulos.modulos.temas_contenido', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '138', 'name' => 'modulos.modulos.temas_administrar', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '137', 'name' => 'modulos.generar_certificado', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '136', 'name' => 'configuracion.estado_registros_pagina_principal', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '135', 'name' => 'modulos.asignar_aprobados_manual', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '134', 'name' => 'modulos.asignar_aprobados_automatico', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '133', 'name' => 'estado_registros_pagina_principal', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '132', 'name' => 'email.ver', 'tipo' => 'permiso', 'guard_name' => 'web' ],
            ['id' => '131', 'name' => 'Ver Email', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => '130', 'name' => 'Email', 'tipo' => 'seccion', 'guard_name' => 'web' ],
            ['id' => '129', 'name' => 'Pagos Estudiantes', 'tipo' => 'menu', 'guard_name' => 'web' ],
            ['id' => '128', 'name' => 'Credenciales', 'tipo' => 'menu', 'guard_name' => 'web' ],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}