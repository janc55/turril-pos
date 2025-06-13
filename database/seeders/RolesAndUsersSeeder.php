<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que las sucursales existan.
        // Si BranchesSeeder se ejecuta antes en DatabaseSeeder, esto ya debería estar listo.
        $centralBranch = Branch::firstOrCreate(['name' => 'Oficina Central / Almacén Mayor']);
        $pradoBranch = Branch::firstOrCreate(['name' => 'Sucursal Ayacucho']);

        // 1. Crear Usuarios
        // Usuario Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'josenegretti@gmail.com'],
            [
                'name' => 'Jose Negretti',
                'password' => Hash::make('password'), // Contraseña simple para desarrollo
                'email_verified_at' => now(),
                'branch_id' => $centralBranch->id, // Asignar al almacén central
                'active' => true,
            ]
        );
        // NOTA: La asignación del rol 'Administrador' (de Spatie) para este usuario
        // se hará en el SpatiePermissionsSeeder, NO AQUÍ.

        // Ejemplo de Gerente
        $managerUser = User::firstOrCreate(
            ['email' => 'gerente@turril.com'],
            [
                'name' => 'Gerente Sucursal',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'branch_id' => $pradoBranch->id, // Asignar a sucursal El Prado
                'active' => true,
            ]
        );
        // NOTA: La asignación del rol 'Gerente' (de Spatie) para este usuario
        // se hará en el SpatiePermissionsSeeder, NO AQUÍ.

        // Ejemplo de Cajero
        $cashierUser = User::firstOrCreate(
            ['email' => 'cajero@turril.com'],
            [
                'name' => 'Cajero Principal',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'branch_id' => $pradoBranch->id, // Asignar a sucursal El Prado
                'active' => true,
            ]
        );
        // NOTA: La asignación del rol 'Cajero' (de Spatie) para este usuario
        // se hará en el SpatiePermissionsSeeder, NO AQUÍ.

        // Hemos eliminado:
        // - Las líneas de `Role::firstOrCreate`
        // - Las líneas de `$user->roles()->syncWithoutDetaching()`
    }
}
