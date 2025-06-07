<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    private function crearUsuarioAutenticado($rolNombre, $permisos = [])
    {
        // Crear o buscar el rol
        $rol = Role::firstOrCreate(['name' => $rolNombre]);

        // Asignar permisos al rol
        foreach ($permisos as $permisoNombre) {
            $permiso = Permission::firstOrCreate(['name' => $permisoNombre]);
            $rol->givePermissionTo($permiso);
        }

        // Crear usuario y asignar rol
        $usuario = User::factory()->create();
        $usuario->assignRole($rol);
        $this->actingAs($usuario);

        return $usuario;
    }

    public function test_registro_usuario()
    {
        $this->crearUsuarioAutenticado('admin', ['Administración de Usuarios', 'usuarios.ver']);

        $faker = Faker::create();

        $data = [
            'name' => '', // dato inválido para que falle la validación
            'email' => 'asdsad', // inválido
            'usuario_nombres' => null, // inválido
            'usuario_app' => '', // inválido
            'usuario_apm' => '', // inválido
            'usuario_telefono' => 'sdfsdf', // inválido, no numérico
            'usuario_direccion' => str_repeat('A', 300), // válido o no según regla (max 1000 es válido)
            'role' => 'admin',
        ];

        $response = $this->post(route('users.store'), $data);

        $response->assertStatus(302); // redirección tras error de validación
        $response->assertSessionHasErrors([
            'name',
            'email',
            'usuario_nombres',
            'usuario_app',
            'usuario_apm',
            'usuario_telefono',
        ]);

        // Para ver los errores en consola durante la prueba (opcional)
        dump(session('errors')->all());
    }

}
