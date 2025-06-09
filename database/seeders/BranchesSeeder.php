<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name' => 'Oficina Central / Almacén Mayor',
            'address' => 'Dirección Central S/N',
            'phone' => '12345678',
            'email' => 'central@turril.com',
            'description' => 'Sucursal virtual para compras centralizadas y gestión de stock principal.',
            'active' => true,
        ]);

        Branch::create([
            'name' => 'Sucursal El Prado',
            'address' => 'Av. 16 de Julio #123',
            'phone' => '23456789',
            'email' => 'prado@turril.com',
            'description' => 'Sucursal principal en la zona céntrica.',
            'active' => true,
        ]);

        Branch::create([
            'name' => 'Sucursal Zona Norte',
            'address' => 'Calle Los Álamos #45',
            'phone' => '34567890',
            'email' => 'norte@turril.com',
            'description' => 'Sucursal en el área comercial del norte.',
            'active' => true,
        ]);
    }
}
