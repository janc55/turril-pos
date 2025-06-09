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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku', 50)->unique()->nullable();
            $table->decimal('price', 10, 2); // Precio de venta al público
            $table->decimal('cost', 10, 2)->nullable(); // Costo de producción/adquisición
            $table->enum('type', ['sandwich', 'drink', 'combo', 'other'])->default('other'); // Añadido 'combo'
            $table->string('image')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('stock_management')->default(true); // Si se gestiona el stock de este producto
            $table->boolean('is_combo')->default(false); // Para identificar combos fácilmente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
