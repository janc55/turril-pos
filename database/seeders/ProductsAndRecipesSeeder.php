<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsAndRecipesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener ingredientes (asegúrate de que IngredientsSeeder se ejecute antes)
        $polloDesmenuzado = Ingredient::where('name', 'Pollo Desmenuzado')->first();
        $cerdoDesmenuzado = Ingredient::where('name', 'Cerdo Desmenuzado')->first();
        $panPequeno = Ingredient::where('name', 'Pan Pequeño')->first();
        $panGrande = Ingredient::where('name', 'Pan Grande')->first();
        // Asegúrate de que todos los ingredientes necesarios existen

        // --- Sandwiches ---

        // Turrilito de Pollo (60gr)
        $turrilitoPollo = Product::firstOrCreate(
            ['name' => 'Turrilito de Pollo (60gr)'],
            [
                'description' => 'Delicioso sandwich de pollo desmenuzado en pan pequeño.',
                'price' => 10.00, // Precio de venta sugerido
                'cost' => null, // Se calculará dinámicamente con la receta
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeTurrilitoPollo = Recipe::firstOrCreate(
            ['product_id' => $turrilitoPollo->id],
            ['name' => 'Receta Turrilito de Pollo', 'description' => 'Receta estándar para el Turrilito de Pollo']
        );
        $recipeTurrilitoPollo->ingredients()->syncWithoutDetaching([
            $polloDesmenuzado->id => ['quantity' => 60],
            $panPequeno->id => ['quantity' => 1],
        ]);


        // Turrilito de Cerdo (60gr)
        $turrilitoCerdo = Product::firstOrCreate(
            ['name' => 'Turrilito de Cerdo (60gr)'],
            [
                'description' => 'Delicioso sandwich de cerdo desmenuzado en pan pequeño.',
                'price' => 10.00,
                'cost' => null,
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeTurrilitoCerdo = Recipe::firstOrCreate(
            ['product_id' => $turrilitoCerdo->id],
            ['name' => 'Receta Turrilito de Cerdo', 'description' => 'Receta estándar para el Turrilito de Cerdo']
        );
        $recipeTurrilitoCerdo->ingredients()->syncWithoutDetaching([
            $cerdoDesmenuzado->id => ['quantity' => 60],
            $panPequeno->id => ['quantity' => 1],
        ]);

        // Turrilazo de Pollo (80gr)
        $turrilazoPollo = Product::firstOrCreate(
            ['name' => 'Turrilazo de Pollo (80gr)'],
            [
                'description' => 'Turrilazo con más pollo desmenuzado en pan grande.',
                'price' => 15.00,
                'cost' => null,
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeTurrilazoPollo = Recipe::firstOrCreate(
            ['product_id' => $turrilazoPollo->id],
            ['name' => 'Receta Turrilazo de Pollo', 'description' => 'Receta estándar para el Turrilazo de Pollo']
        );
        $recipeTurrilazoPollo->ingredients()->syncWithoutDetaching([
            $polloDesmenuzado->id => ['quantity' => 80],
            $panGrande->id => ['quantity' => 1],
        ]);

        // Turrilazo de Cerdo (80gr)
        $turrilazoCerdo = Product::firstOrCreate(
            ['name' => 'Turrilazo de Cerdo (80gr)'],
            [
                'description' => 'Turrilazo con más cerdo desmenuzado en pan grande.',
                'price' => 15.00,
                'cost' => null,
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeTurrilazoCerdo = Recipe::firstOrCreate(
            ['product_id' => $turrilazoCerdo->id],
            ['name' => 'Receta Turrilazo de Cerdo', 'description' => 'Receta estándar para el Turrilazo de Cerdo']
        );
        $recipeTurrilazoCerdo->ingredients()->syncWithoutDetaching([
            $cerdoDesmenuzado->id => ['quantity' => 80],
            $panGrande->id => ['quantity' => 1],
        ]);

        // Super Turril de Pollo (120gr)
        $superTurrilPollo = Product::firstOrCreate(
            ['name' => 'Super Turril de Pollo (120gr)'],
            [
                'description' => 'El más grande! Mucho pollo desmenuzado en pan grande.',
                'price' => 20.00,
                'cost' => null,
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeSuperTurrilPollo = Recipe::firstOrCreate(
            ['product_id' => $superTurrilPollo->id],
            ['name' => 'Receta Super Turril de Pollo', 'description' => 'Receta para el Super Turril de Pollo']
        );
        $recipeSuperTurrilPollo->ingredients()->syncWithoutDetaching([
            $polloDesmenuzado->id => ['quantity' => 120],
            $panGrande->id => ['quantity' => 1],
        ]);

        // Super Turril de Cerdo (120gr)
        $superTurrilCerdo = Product::firstOrCreate(
            ['name' => 'Super Turril de Cerdo (120gr)'],
            [
                'description' => 'El más grande! Mucho cerdo desmenuzado en pan grande.',
                'price' => 20.00,
                'cost' => null,
                'type' => 'sandwich',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
        $recipeSuperTurrilCerdo = Recipe::firstOrCreate(
            ['product_id' => $superTurrilCerdo->id],
            ['name' => 'Receta Super Turril de Cerdo', 'description' => 'Receta para el Super Turril de Cerdo']
        );
        $recipeSuperTurrilCerdo->ingredients()->syncWithoutDetaching([
            $cerdoDesmenuzado->id => ['quantity' => 120],
            $panGrande->id => ['quantity' => 1],
        ]);


        // --- Bebidas ---
        Product::firstOrCreate(
            ['name' => 'Coca Cola Mini 120 ml'],
            [
                'description' => 'Coca Cola en formato mini.',
                'price' => 4.00,
                'cost' => 2.00,
                'type' => 'drink',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Coca Cola 300 ml'],
            [
                'description' => 'Coca Cola individual de 300ml.',
                'price' => 6.00,
                'cost' => 3.00,
                'type' => 'drink',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Fanta 300ml'],
            [
                'description' => 'Fanta individual de 300ml.',
                'price' => 6.00,
                'cost' => 3.00,
                'type' => 'drink',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Refresco de la Temporada'],
            [
                'description' => 'Refresco natural de temporada, disponible solo por tiempo limitado.',
                'price' => 5.00,
                'cost' => 2.50,
                'type' => 'drink',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Café Mediano'],
            [
                'description' => 'Café caliente de tamaño mediano.',
                'price' => 8.00,
                'cost' => 4.00,
                'type' => 'drink',
                'active' => true,
                'stock_management' => true,
                'is_combo' => false,
            ]
        );
    }
}
