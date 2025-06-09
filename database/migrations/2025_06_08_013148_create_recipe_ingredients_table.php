<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipe_ingredients', function (Blueprint $table) {
           // **NUEVO**: Agregamos una clave primaria auto-incrementable 'id'
            $table->id();

            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity', 10, 4); // Cantidad de este ingrediente en la receta
            $table->timestamps();

            // **IMPORTANTE**: Eliminamos la clave primaria compuesta
            // La columna 'id' ahora será la clave primaria principal para Filament
            // y para referenciar registros individuales en esta tabla.

            // Puedes agregar un índice único si quieres asegurar que una receta no tenga el mismo ingrediente dos veces.
            $table->unique(['recipe_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
