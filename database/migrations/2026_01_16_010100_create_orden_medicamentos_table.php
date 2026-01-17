<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orden_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordenes_medicas')->cascadeOnDelete();
            
            $table->string('medicamento', 255);
            $table->string('presentacion', 100)->nullable(); // Ej: "Tabletas 500mg"
            $table->integer('cantidad')->default(1);
            $table->string('dosis', 100)->nullable(); // Ej: "1 cada 8 horas"
            $table->enum('via_administracion', [
                'Oral', 'Sublingual', 'Intravenosa', 'Intramuscular', 
                'Subcutanea', 'Topica', 'Oftalmica', 'Otica', 
                'Nasal', 'Inhalatoria', 'Rectal', 'Vaginal', 'Otra'
            ])->default('Oral');
            $table->integer('duracion_dias')->nullable();
            $table->text('indicaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('orden_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orden_medicamentos');
    }
};
