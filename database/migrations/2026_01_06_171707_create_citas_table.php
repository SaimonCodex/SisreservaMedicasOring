<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('paciente_id')->constrained('pacientes')->onUpdate('cascade');
            $table->foreignId('medico_id')->constrained('medicos')->onUpdate('cascade');
            $table->foreignId('especialidad_id')->constrained('especialidades')->onUpdate('cascade');
            $table->foreignId('consultorio_id')->nullable()->constrained('consultorios')->onUpdate('cascade');
            
            $table->date('fecha_cita');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->smallInteger('duracion_minutos')->nullable();
            
            $table->decimal('tarifa', 10, 2)->default(0.00);
            $table->enum('tipo_consulta', ['Presencial', 'Telemedicina', 'Domicilio'])->default('Presencial');
            $table->enum('estado_cita', ['Programada', 'Confirmada', 'En Progreso', 'Completada', 'Cancelada', 'No Asistió'])->default('Programada');
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['fecha_cita', 'medico_id']);
            $table->index(['paciente_id', 'fecha_cita']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
