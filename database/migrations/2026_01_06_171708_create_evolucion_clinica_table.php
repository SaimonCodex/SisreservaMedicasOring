<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evolucion_clinica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas');
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos');
            
            $table->decimal('peso_kg', 5, 2)->nullable();
            $table->decimal('talla_cm', 5, 2)->nullable();
            $table->decimal('imc', 5, 2)->nullable();
            $table->integer('tension_sistolica')->nullable();
            $table->integer('tension_diastolica')->nullable();
            $table->integer('frecuencia_cardiaca')->nullable();
            $table->decimal('temperatura_c', 4, 2)->nullable();
            $table->integer('frecuencia_respiratoria')->nullable();
            $table->decimal('saturacion_oxigeno', 5, 2)->nullable();
            
            $table->string('motivo_consulta', 255);
            $table->text('enfermedad_actual');
            $table->text('examen_fisico')->nullable();
            $table->text('diagnostico');
            $table->text('tratamiento');
            $table->text('recomendaciones')->nullable();
            $table->text('notas_adicionales')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['paciente_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evolucion_clinica');
    }
};
