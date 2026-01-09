<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_seguridad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas_catalogo')->onDelete('restrict')->onUpdate('cascade');
            $table->string('respuesta_hash');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'pregunta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_seguridad');
    }
};
