<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SeederPermisos_20250607 extends Seeder
{
    public function run()
    {
        $permisos = [
            ['id' => '161', 'name' => 'contenido.contenido_tema_eliminar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '160', 'name' => 'inscripcion.guardar_inscripcion_estudiante', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '159', 'name' => 'inscripcion.inscribir_estudiante', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '158', 'name' => 'Inscribir estudiante', 'tipo' => 'menu', 'guard_name' => 'web', 'id_relacion' => 28,],
            ['id' => '157', 'name' => 'estudiante.cambiar_estado', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '156', 'name' => 'estudiante.pagos_materias', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '155', 'name' => 'estudiante.ver_detalle', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '154', 'name' => 'estudiante.ver_inactivos', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '153', 'name' => 'estudiante.asignar_paralelo', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '152', 'name' => 'asistencia.editar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '151', 'name' => 'asistencia.ver_detalle', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '150', 'name' => 'asistencia.generar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '149', 'name' => 'modulos.modulos.temas_eliminar_contenido', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '148', 'name' => 'modulos.modulos.temas_obtener', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '147', 'name' => 'modulos.modulos.temas_guardar_contenido', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '146', 'name' => 'modulos.modulos.temas_guardar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '145', 'name' => 'modulos.modulos.temas_crear', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '144', 'name' => 'modulos.modulos.temas_eliminar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '143', 'name' => 'modulos.modulos.temas_actualizar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '142', 'name' => 'modulos.modulos.temas_editar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '141', 'name' => 'modulos.modulos.temas_finalizar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '140', 'name' => 'modulos.modulos.temas_detalles', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '139', 'name' => 'modulos.modulos.temas_contenido', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null],
            ['id' => '138', 'name' => 'modulos.modulos.temas_administrar', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '137', 'name' => 'modulos.generar_certificado', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '136', 'name' => 'configuracion.estado_registros_pagina_principal', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '135', 'name' => 'modulos.asignar_aprobados_manual', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '134', 'name' => 'modulos.asignar_aprobados_automatico', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '133', 'name' => 'estado_registros_pagina_principal', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '132', 'name' => 'email.ver', 'tipo' => 'permiso', 'guard_name' => 'web', 'id_relacion' => null,],
            ['id' => '131', 'name' => 'Ver Email', 'tipo' => 'menu', 'guard_name' => 'web', 'id_relacion' => 27,],
            ['id' => '130', 'name' => 'Email', 'tipo' => 'seccion', 'guard_name' => 'web', 'id_relacion' => 21,],
            ['id' => '129', 'name' => 'Pagos Estudiantes', 'tipo' => 'menu', 'guard_name' => 'web', 'id_relacion' => 26,],
            ['id' => '128', 'name' => 'Credenciales', 'tipo' => 'menu', 'guard_name' => 'web', 'id_relacion' => 25,],
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(
                ['name' => $permiso['name'], 'tipo' => $permiso['tipo']],
                $permiso
            );
        }
    }
}