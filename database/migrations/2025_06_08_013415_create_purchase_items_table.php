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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->onDelete('cascade'); // Ingrediente comprado
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade'); // Producto terminado comprado
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 10, 2); // Costo del ítem en el momento de la compra
            $table->decimal('subtotal', 10, 2); // quantity * unit_cost
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
