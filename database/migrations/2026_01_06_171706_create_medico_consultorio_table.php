<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medico_consultorio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos')->onUpdate('cascade');
            $table->foreignId('consultorio_id')->constrained('consultorios')->onUpdate('cascade');
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']);
            $table->enum('turno', ['mañana', 'tarde', 'noche', 'completo']);
            $table->time('horario_inicio');
            $table->time('horario_fin');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['medico_id', 'dia_semana', 'consultorio_id'], 'medico_horario_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medico_consultorio');
    }
};
