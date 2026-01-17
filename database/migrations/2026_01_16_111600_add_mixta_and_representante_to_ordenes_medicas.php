<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar 'Mixta' al enum tipo_orden
        DB::statement("ALTER TABLE ordenes_medicas MODIFY COLUMN tipo_orden ENUM('Receta', 'Laboratorio', 'Imagenologia', 'Referencia', 'Interconsulta', 'Procedimiento', 'Mixta') NOT NULL");

        // Agregar representante_id para órdenes de pacientes especiales
        Schema::table('ordenes_medicas', function (Blueprint $table) {
            $table->foreignId('representante_id')->nullable()->after('paciente_especial_id')
                  ->constrained('representantes')->nullOnDelete();
            
            $table->index('representante_id');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes_medicas', function (Blueprint $table) {
            $table->dropForeign(['representante_id']);
            $table->dropIndex(['representante_id']);
            $table->dropColumn('representante_id');
        });

        // Revertir el enum (sin Mixta)
        DB::statement("ALTER TABLE ordenes_medicas MODIFY COLUMN tipo_orden ENUM('Receta', 'Laboratorio', 'Imagenologia', 'Referencia', 'Interconsulta', 'Procedimiento') NOT NULL");
    }
};
