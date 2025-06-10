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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit', 50); // e.g., 'gramos', 'ml', 'unidades', 'piezas'
            $table->decimal('cost_per_unit', 10, 4)->nullable(); // Costo por unidad
            //$table->decimal('cost_average', 10, 4)->nullable(); Agregado para calcular el costo promedio de los ingredientes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
