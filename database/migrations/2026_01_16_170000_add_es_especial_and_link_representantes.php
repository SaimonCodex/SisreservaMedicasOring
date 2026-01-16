<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar campo es_especial a pacientes
        Schema::table('pacientes', function (Blueprint $table) {
            $table->tinyInteger('es_especial')->default(0)->nullable()->after('status');
        });

        // 2. Agregar paciente_id a representantes para vincular con cuenta de paciente
        Schema::table('representantes', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->after('id')
                  ->constrained('pacientes')->nullOnDelete();
        });

        // 3. Actualizar pacientes existentes que ya son especiales
        DB::statement("
            UPDATE pacientes p
            INNER JOIN pacientes_especiales pe ON pe.paciente_id = p.id
            SET p.es_especial = 1
            WHERE pe.status = 1
        ");

        // 4. Vincular representantes existentes con pacientes por documento
        DB::statement("
            UPDATE representantes r
            INNER JOIN pacientes p ON 
                r.numero_documento = p.numero_documento 
                AND r.tipo_documento = p.tipo_documento
            SET r.paciente_id = p.id
            WHERE r.paciente_id IS NULL
            AND p.numero_documento IS NOT NULL
            AND r.numero_documento IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('representantes', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropColumn('paciente_id');
        });

        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn('es_especial');
        });
    }
};
