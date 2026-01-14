<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrador_consultorio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('administrador_id')->constrained('administradores')->onDelete('cascade');
            $table->foreignId('consultorio_id')->constrained('consultorios')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['administrador_id', 'consultorio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrador_consultorio');
    }
};
