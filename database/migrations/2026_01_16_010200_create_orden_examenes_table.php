<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes_medicas')->cascadeOnDelete();
            
            $table->enum('tipo_examen', [
                'Hematologia', 'Bioquimica', 'Uroanalisis', 'Coprologia',
                'Inmunologia', 'Microbiologia', 'Hormonas', 'Marcadores Tumorales',
                'Coagulacion', 'Gases Arteriales', 'Serologia', 'Otro'
            ]);
            $table->string('nombre_examen', 255);
            $table->boolean('urgente')->default(false);
            $table->text('indicacion_clinica')->nullable();
            
            // Campos para resultados
            $table->text('resultado')->nullable();
            $table->date('fecha_resultado')->nullable();
            $table->string('laboratorio', 255)->nullable();
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('orden_id');
            $table->index('tipo_examen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_examenes');
    }
};
