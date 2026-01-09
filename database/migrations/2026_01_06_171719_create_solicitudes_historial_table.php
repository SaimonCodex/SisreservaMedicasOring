<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas');
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_solicitante_id')->constrained('medicos');
            $table->foreignId('medico_propietario_id')->constrained('medicos');
            
            $table->string('token_validacion', 10);
            $table->dateTime('token_expira_at');
            $table->tinyInteger('intentos_fallidos')->default(0);
            
            $table->enum('motivo_solicitud', ['Interconsulta', 'Emergencia', 'Segunda Opinion', 'Referencia']);
            $table->enum('estado_permiso', ['Pendiente', 'Aprobado', 'Rechazado', 'Expirado'])->default('Pendiente');
            $table->dateTime('acceso_valido_hasta')->nullable();
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['token_validacion', 'estado_permiso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_historial');
    }
};
