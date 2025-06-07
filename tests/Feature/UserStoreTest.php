<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Crear rol necesario para la prueba
        Role::create(['name' => 'admin']);
    }

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

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_crear_un_usuario_con_datos_validos()
    {
        $this->crearUsuarioAutenticado('admin', ['Administración de Usuarios', 'usuarios.ver']);



        $response = $this->post(route('users.store'), [
            'name' => 'Usuario Prueba',
            'email' => 'usuario@prueba.com',
            'usuario_nombres' => 'Nombre',
            'usuario_app' => 'ApellidoP',
            'usuario_apm' => 'ApellidoM',
            'usuario_telefono' => 'rgd',  // dato válido
            'usuario_direccion' => 'Calle Falsa 123',
            'role' => 'admin',
        ]);

        // Verifica que no hay errores de validación
        $response->assertSessionDoesntHaveErrors();

        // Verifica que redirige a la lista de usuarios
        $response->assertRedirect(route('users.index'));



    }

}
