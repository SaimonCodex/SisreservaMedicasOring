<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_medicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas');
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos');
            
            $table->enum('tipo_orden', ['Receta', 'Laboratorio', 'Imagenologia', 'Referencia', 'Interconsulta', 'Procedimiento']);
            $table->text('descripcion_detallada');
            $table->text('indicaciones')->nullable();
            $table->text('resultados')->nullable();
            $table->date('fecha_emision');
            $table->date('fecha_vigencia')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('paciente_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_medicas');
    }
};
