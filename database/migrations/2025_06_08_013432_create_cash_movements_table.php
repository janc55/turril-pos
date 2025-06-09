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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_box_id')->constrained('cash_boxes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Quién realizó el movimiento
            $table->enum('type', ['deposit', 'withdrawal', 'sale_income', 'purchase_payment', 'other_income', 'other_expense']);
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->foreignId('related_sale_id')->nullable()->constrained('sales')->onDelete('set null');
            $table->foreignId('related_purchase_id')->nullable()->constrained('purchases')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
