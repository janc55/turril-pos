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
        Schema::create('cash_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('initial_balance', 10, 2)->default(0.00);
            $table->decimal('current_balance', 10, 2)->default(0.00); // Puede ser calculado
            $table->boolean('status')->default(false); // Cambiado a boolean
            $table->foreignId('user_id')->nullable()->constrained('users'); // Agrega la clave foránea para el usuario del turno
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_boxes');
    }
};
