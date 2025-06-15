<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class SeccionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('secciones')->insert([
            [
                'id' => 5,
                'posicion' => 1,
                'titulo' => 'Administración de Usuarios',
                'icono' => 'fas fa-users',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-04-09 19:59:32'),
                'updated_at' => Carbon::parse('2025-04-09 19:59:32'),
            ],
            [
                'id' => 6,
                'posicion' => 2,
                'titulo' => 'Configuración',
                'icono' => 'fas fa-cog',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-04-11 18:43:25'),
                'updated_at' => Carbon::parse('2025-04-11 18:43:25'),
            ],
            [
                'id' => 7,
                'posicion' => 3,
                'titulo' => 'Roles y Permisos',
                'icono' => 'fas fa-user-lock',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-04-14 18:42:46'),
                'updated_at' => Carbon::parse('2025-04-14 18:42:46'),
            ],
            [
                'id' => 8,
                'posicion' => 4,
                'titulo' => 'Seccion',
                'icono' => 'user',
                'accion_usuario' => 'admin4',
                'created_at' => Carbon::parse('2025-05-02 18:21:40'),
                'updated_at' => Carbon::parse('2025-05-02 18:21:40'),
            ],
            [
                'id' => 10,
                'posicion' => 5,
                'titulo' => 'Administración y Parametrización',
                'icono' => 'fas fa-cogs',
                'accion_usuario' => 'admin4',
                'created_at' => Carbon::parse('2025-05-02 18:38:17'),
                'updated_at' => Carbon::parse('2025-05-02 18:38:17'),
            ],
            [
                'id' => 13,
                'posicion' => 6,
                'titulo' => 'Materias y Modulos',
                'icono' => 'fas fa-book',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-01 18:16:14'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 14,
                'posicion' => 7,
                'titulo' => 'inscripciones',
                'icono' => 'fas fa-edit',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-03 00:06:03'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 17,
                'posicion' => 8,
                'titulo' => 'Administración de Pagos',
                'icono' => 'fas fa-credit-card',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-04 23:59:40'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 18,
                'posicion' => 9,
                'titulo' => 'Informacion Personal',
                'icono' => 'fas fa-id-card',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-05 00:00:51'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 19,
                'posicion' => 10,
                'titulo' => 'Profesores',
                'icono' => 'fas fa-chalkboard-teacher',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-05 00:03:03'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 20,
                'posicion' => 11,
                'titulo' => 'Estudiante',
                'icono' => 'fas fa-graduation-cap',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-05 00:04:10'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
            [
                'id' => 21,
                'posicion' => 12,
                'titulo' => 'Email',
                'icono' => 'fas fa-envelope',
                'accion_usuario' => 'admin',
                'created_at' => Carbon::parse('2025-06-07 02:29:40'),
                'updated_at' => Carbon::parse('2025-06-10 04:27:37'),
            ],
        ]);
    }
}
