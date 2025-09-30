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
            'phone' => '72487697',
            'email' => 'central@turril.com',
            'description' => 'Sucursal virtual para compras centralizadas y gestión de stock principal.',
            'active' => true,
        ]);

        Branch::create([
            'name' => 'Sucursal Ayacucho',
            'address' => 'Ayacucho entre 6 de octubre y Soria Galvarro',
            'phone' => '77150404',
            'email' => 'ayacucho@elturril.com',
            'description' => 'Sucursal principal en la zona céntrica.',
            'active' => true,
        ]);
    }
}
