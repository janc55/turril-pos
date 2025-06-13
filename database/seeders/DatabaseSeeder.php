<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Importante: El orden es crucial para las claves foráneas
        $this->call([
            BranchesSeeder::class,         // Necesario para que users y otros puedan asignar branches
            RolesAndUsersSeeder::class,    // Crea roles y el usuario admin
            IngredientsSeeder::class,      // Crea todos los ingredientes y subproductos
            ProductsAndRecipesSeeder::class, // Crea productos y sus recetas (depende de Ingredients)
            SpatiePermissionsSeeder::class, // Crea permisos y asigna roles a usuarios
            // Aquí puedes añadir más seeders en el futuro, como ComboItemsSeeder
        ]);
    }
}
