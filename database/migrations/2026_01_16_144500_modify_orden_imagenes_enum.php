<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the enum to include 'Electrocardiograma'
        DB::statement("ALTER TABLE orden_imagenes MODIFY COLUMN tipo_estudio ENUM('Rayos X', 'Ecografia', 'TAC', 'Resonancia Magnetica', 'Mamografia', 'Densitometria', 'Gammagrafia', 'PET', 'Angiografia', 'Fluoroscopia', 'Otro', 'Electrocardiograma')");
    }

    public function down(): void
    {
        // Revert to original enum (warning: data loss for 'Electrocardiograma' if any exist)
        DB::statement("ALTER TABLE orden_imagenes MODIFY COLUMN tipo_estudio ENUM('Rayos X', 'Ecografia', 'TAC', 'Resonancia Magnetica', 'Mamografia', 'Densitometria', 'Gammagrafia', 'PET', 'Angiografia', 'Fluoroscopia', 'Otro')");
    }
};
