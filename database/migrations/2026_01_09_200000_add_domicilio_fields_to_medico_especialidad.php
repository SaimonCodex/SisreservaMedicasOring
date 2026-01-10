<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medico_especialidad', function (Blueprint $table) {
            $table->boolean('atiende_domicilio')->default(false)->after('tarifa');
            $table->decimal('tarifa_extra_domicilio', 10, 2)->default(0.00)->after('atiende_domicilio');
        });

        // También actualizar el enum de tipo_consulta en citas para quitar Online
        Schema::table('citas', function (Blueprint $table) {
            // Agregar campo para paciente especial y representante
            $table->foreignId('paciente_especial_id')->nullable()->after('paciente_id')->constrained('pacientes_especiales')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('representante_id')->nullable()->after('paciente_especial_id')->constrained('representantes')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('tarifa_extra', 10, 2)->default(0.00)->after('tarifa');
            $table->text('motivo')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('medico_especialidad', function (Blueprint $table) {
            $table->dropColumn(['atiende_domicilio', 'tarifa_extra_domicilio']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['paciente_especial_id']);
            $table->dropForeign(['representante_id']);
            $table->dropColumn(['paciente_especial_id', 'representante_id', 'tarifa_extra', 'motivo']);
        });
    }
};
