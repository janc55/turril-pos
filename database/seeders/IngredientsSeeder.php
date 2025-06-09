<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Ingredientes Brutos
        Ingredient::firstOrCreate(['name' => 'Zanahoria'], ['unit' => 'gramos', 'cost_per_unit' => 0.005]); // 5 centavos por gramo
        Ingredient::firstOrCreate(['name' => 'Repollo'], ['unit' => 'gramos', 'cost_per_unit' => 0.003]);
        Ingredient::firstOrCreate(['name' => 'Pollo Entero'], ['unit' => 'kg', 'cost_per_unit' => 15.00]); // 15 Bs por kg
        Ingredient::firstOrCreate(['name' => 'Cerdo Entero'], ['unit' => 'kg', 'cost_per_unit' => 20.00]);
        Ingredient::firstOrCreate(['name' => 'Locoto'], ['unit' => 'unidades', 'cost_per_unit' => 0.50]);
        Ingredient::firstOrCreate(['name' => 'Tomate'], ['unit' => 'gramos', 'cost_per_unit' => 0.004]);
        Ingredient::firstOrCreate(['name' => 'Carbón'], ['unit' => 'kg', 'cost_per_unit' => 2.50]);
        Ingredient::firstOrCreate(['name' => 'Mayonesa'], ['unit' => 'gramos', 'cost_per_unit' => 0.008]);
        Ingredient::firstOrCreate(['name' => 'Pan Pequeño'], ['unit' => 'unidades', 'cost_per_unit' => 0.80]);
        Ingredient::firstOrCreate(['name' => 'Pan Grande'], ['unit' => 'unidades', 'cost_per_unit' => 1.20]);
        Ingredient::firstOrCreate(['name' => 'Ensalada Mixta'], ['unit' => 'gramos', 'cost_per_unit' => 0.01]); // Ingrediente preparado

        // Subproducto: Pollo Desmenuzado (representado como un ingrediente que se "produce")
        // Asumiendo un costo de producción. Esto puede ser más complejo en un sistema real.
        Ingredient::firstOrCreate(
            ['name' => 'Pollo Desmenuzado'],
            ['unit' => 'gramos', 'cost_per_unit' => 0.03] // Por ejemplo, 0.03 Bs/gramo (30 Bs/kg)
        );

        // Subproducto: Cerdo Desmenuzado
        Ingredient::firstOrCreate(
            ['name' => 'Cerdo Desmenuzado'],
            ['unit' => 'gramos', 'cost_per_unit' => 0.035] // Por ejemplo, 0.035 Bs/gramo (35 Bs/kg)
        );
    }
}
