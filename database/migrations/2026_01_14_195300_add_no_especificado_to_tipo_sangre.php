<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar el enum tipo_sangre para agregar 'No Especificado'
        DB::statement("ALTER TABLE historia_clinica_base MODIFY COLUMN tipo_sangre ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-','No Especificado') NULL");
    }

    public function down(): void
    {
        // Revertir al enum original
        DB::statement("ALTER TABLE historia_clinica_base MODIFY COLUMN tipo_sangre ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NULL");
    }
};
