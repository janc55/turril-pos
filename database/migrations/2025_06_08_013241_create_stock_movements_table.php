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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade'); // Para productos terminados
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->onDelete('cascade'); // Para ingredientes
            $table->enum('type', ['entry', 'exit', 'adjustment', 'transfer_in', 'transfer_out']);
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50)->nullable(); // Unidad en la que se mueve el ítem
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quién realizó el movimiento
            $table->text('description')->nullable();
            $table->timestamps();

            // Añadir índices para mejorar el rendimiento de las consultas
            $table->index(['product_id']);
            $table->index(['ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
