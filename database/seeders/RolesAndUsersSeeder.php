<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
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
        // 1. Crear Roles
        $adminRole = Role::firstOrCreate(['name' => 'Administrador'], ['description' => 'Control total del sistema']);
        $managerRole = Role::firstOrCreate(['name' => 'Gerente'], ['description' => 'Gestión de sucursales y personal']);
        $cashierRole = Role::firstOrCreate(['name' => 'Cajero'], ['description' => 'Manejo de punto de venta y caja']);
        $warehouseRole = Role::firstOrCreate(['name' => 'Almacenero'], ['description' => 'Gestión de inventario y movimientos de stock']);

        // Asegúrate de que las sucursales existan o corre BranchesSeeder primero
        $centralBranch = Branch::firstOrCreate(['name' => 'Oficina Central / Almacén Mayor']);
        $pradoBranch = Branch::firstOrCreate(['name' => 'Sucursal El Prado']);

        // 2. Crear Usuarios y Asignar Roles
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
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);

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
        $managerUser->roles()->syncWithoutDetaching([$managerRole->id]);

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
        $cashierUser->roles()->syncWithoutDetaching([$cashierRole->id]);
    
    }
}
