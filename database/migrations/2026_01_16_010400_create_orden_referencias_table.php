<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_referencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes_medicas')->cascadeOnDelete();
            
            $table->string('especialidad_destino', 100);
            $table->foreignId('medico_referido_id')->nullable()
                  ->constrained('medicos')->nullOnDelete(); // Médico específico (opcional)
            
            $table->text('motivo_referencia');
            $table->text('resumen_clinico'); // Historia resumida para el especialista
            $table->enum('prioridad', ['Normal', 'Preferente', 'Urgente'])->default('Normal');
            
            // Campos para respuesta/seguimiento
            $table->text('respuesta')->nullable(); // Respuesta del especialista
            $table->date('fecha_atencion')->nullable();
            $table->text('recomendaciones_especialista')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('orden_id');
            $table->index('especialidad_destino');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_referencias');
    }
};
