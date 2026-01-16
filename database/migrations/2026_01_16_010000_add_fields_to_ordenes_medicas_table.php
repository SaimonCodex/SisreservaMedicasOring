<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes_medicas', function (Blueprint $table) {
            // Soporte para pacientes especiales
            $table->foreignId('paciente_especial_id')->nullable()->after('paciente_id')
                  ->constrained('pacientes_especiales')->nullOnDelete();
            
            // Código único de orden (ORD-2026-0001)
            $table->string('codigo_orden', 20)->nullable()->unique()->after('id');
            
            // Estado de procesamiento
            $table->enum('estado_orden', ['Emitida', 'Parcialmente Procesada', 'Procesada', 'Cancelada'])
                  ->default('Emitida')->after('fecha_vigencia');
            
            // Diagnóstico principal asociado
            $table->text('diagnostico_principal')->nullable()->after('estado_orden');
            
            // Firma digital para trazabilidad
            $table->text('firma_digital')->nullable()->after('diagnostico_principal');
            
            // Fecha de procesamiento
            $table->datetime('fecha_procesamiento')->nullable()->after('firma_digital');
            
            // Especialidad del médico al momento de emitir
            $table->foreignId('especialidad_id')->nullable()->after('medico_id')
                  ->constrained('especialidades')->nullOnDelete();
            
            // Índices adicionales
            $table->index('codigo_orden');
            $table->index('estado_orden');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes_medicas', function (Blueprint $table) {
            $table->dropForeign(['paciente_especial_id']);
            $table->dropForeign(['especialidad_id']);
            $table->dropIndex(['codigo_orden']);
            $table->dropIndex(['estado_orden']);
            
            $table->dropColumn([
                'paciente_especial_id',
                'codigo_orden',
                'estado_orden',
                'diagnostico_principal',
                'firma_digital',
                'fecha_procesamiento',
                'especialidad_id'
            ]);
        });
    }
};
