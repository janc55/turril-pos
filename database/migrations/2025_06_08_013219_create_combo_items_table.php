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
        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_product_id')->constrained('products')->onDelete('cascade'); // El producto 'combo'
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade'); // El producto individual que compone el combo
            $table->integer('quantity')->unsigned(); // Cantidad de este producto en el combo
            $table->integer('min_choices')->default(1); // Mínimo de opciones si se permite elegir
            $table->integer('max_choices')->default(1); // Máximo de opciones si se permite elegir
            $table->boolean('is_customizable')->default(false); // Si el cliente puede elegir entre opciones
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_items');
    }
};
