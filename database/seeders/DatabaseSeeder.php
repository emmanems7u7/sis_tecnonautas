<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {



        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $this->call(class: RolesPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategoriasSeeder::class);
        $this->call(CatalogoSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ConfiguracionSeeder::class);
        $this->call(ConfCorreoSeeder::class);
        $this->call(SeccionesSeeder::class);
        $this->call(MenusSeeder::class);
        $this->call(ConfiguracionCredencialesSeeder::class);
        $this->call(registroVerificaSeeder::class);
        $this->call(SeederMenu_20250605::class);
        $this->call(SeederMenu_20250607::class);
        $this->call(SeederPermisos_20250603::class);
        $this->call(SeederPermisos_20250604::class);
        $this->call(SeederPermisos_20250605::class);
        $this->call(SeederPermisos_20250607::class);




    }
}
