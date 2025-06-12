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
        Schema::create('current_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->timestamp('updated_at')->useCurrent(); // Solo updated_at, se actualiza en cada cambio

            // Asegura que solo haya una entrada por ítem y sucursal
            $table->unique(['branch_id', 'product_id']);
            $table->unique(['branch_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_stock');
    }
};
