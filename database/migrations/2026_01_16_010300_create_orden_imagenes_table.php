<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes_medicas')->cascadeOnDelete();
            
            $table->enum('tipo_estudio', [
                'Rayos X', 'Ecografia', 'TAC', 'Resonancia Magnetica',
                'Mamografia', 'Densitometria', 'Gammagrafia', 'PET',
                'Angiografia', 'Fluoroscopia', 'Otro'
            ]);
            $table->string('region_anatomica', 100);
            $table->string('proyecciones', 255)->nullable(); // Vistas requeridas
            $table->boolean('contraste')->default(false);
            $table->boolean('urgente')->default(false);
            $table->text('indicacion_clinica')->nullable();
            
            // Campos para resultados
            $table->text('resultado')->nullable(); // Informe radiológico
            $table->string('archivo_imagen', 255)->nullable(); // Path a imagen/estudio
            $table->date('fecha_resultado')->nullable();
            $table->string('centro_imagenes', 255)->nullable();
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('orden_id');
            $table->index('tipo_estudio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_imagenes');
    }
};
