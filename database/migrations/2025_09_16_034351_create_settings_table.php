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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable(); // Identificador del cliente/local
            $table->string('key')->unique(); // Clave única por tenant
            $table->text('value')->nullable(); // Valor de la configuración
            $table->timestamps();

            // Índice único para evitar duplicados de key por tenant
            $table->unique(['tenant_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
