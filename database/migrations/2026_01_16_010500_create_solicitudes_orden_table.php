<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_orden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes_medicas')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_solicitante_id')->constrained('medicos');
            $table->foreignId('medico_propietario_id')->constrained('medicos');
            
            $table->string('token_validacion', 10);
            $table->dateTime('token_expira_at');
            $table->tinyInteger('intentos_fallidos')->default(0);
            
            $table->enum('motivo_solicitud', [
                'Interconsulta', 'Emergencia', 'Segunda Opinion', 'Referencia', 'Continuidad Tratamiento'
            ]);
            $table->enum('estado_permiso', ['Pendiente', 'Aprobado', 'Rechazado', 'Expirado'])
                  ->default('Pendiente');
            $table->dateTime('acceso_valido_hasta')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['orden_id', 'estado_permiso']);
            $table->index(['medico_solicitante_id', 'estado_permiso']);
            $table->index('token_validacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_orden');
    }
};
