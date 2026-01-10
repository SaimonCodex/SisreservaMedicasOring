<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add Column
        if (!Schema::hasColumn('medico_consultorio', 'especialidad_id')) {
             Schema::table('medico_consultorio', function (Blueprint $table) {
                $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->onDelete('cascade');
             });
        }

        // 2. Drop FK relying on the index
        try {
            Schema::table('medico_consultorio', function (Blueprint $table) {
                $table->dropForeign(['medico_id']);
            });
        } catch (\Exception $e) {}

        // 3. Drop unique index
        try {
             Schema::table('medico_consultorio', function (Blueprint $table) {
                $table->dropUnique('medico_horario_unique');
             });
        } catch (\Exception $e) {}

        // 4. Restore FK
        try {
             Schema::table('medico_consultorio', function (Blueprint $table) {
                $table->foreign('medico_id')->references('id')->on('medicos')->onUpdate('cascade');
             });
        } catch (\Exception $e) {}

        // 5. Add new unique index
        try {
             Schema::table('medico_consultorio', function (Blueprint $table) {
                $table->unique(['medico_id', 'dia_semana', 'turno'], 'medico_horario_turno_unique');
             });
        } catch (\Exception $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medico_consultorio', function (Blueprint $table) {
            $table->dropForeign(['especialidad_id']);
            $table->dropColumn('especialidad_id');
            $table->dropUnique('medico_horario_turno_unique');
            // Restore old index
            $table->unique(['medico_id', 'dia_semana', 'consultorio_id'], 'medico_horario_unique');
        });
    }
};
