<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Actualizar enum tipo_examen para coincidir con los valores del formulario
        // Usamos solo los valores con acentos que usa el formulario
        DB::statement("ALTER TABLE orden_examenes MODIFY COLUMN tipo_examen ENUM(
            'Hematológico', 'Bioquímica', 'Orina', 'Heces', 'Serología', 
            'Hormonal', 'Microbiología', 'Otro', 'Inmunología', 
            'Marcadores Tumorales', 'Coagulación', 'Gases Arteriales'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orden_examenes MODIFY COLUMN tipo_examen ENUM(
            'Hematologia', 'Bioquimica', 'Uroanalisis', 'Coprologia',
            'Inmunologia', 'Microbiologia', 'Hormonas', 'Marcadores Tumorales',
            'Coagulacion', 'Gases Arteriales', 'Serologia', 'Otro'
        ) NOT NULL");
    }
};
