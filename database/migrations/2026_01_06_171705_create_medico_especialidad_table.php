<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medico_especialidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos')->onUpdate('cascade');
            $table->foreignId('especialidad_id')->constrained('especialidades')->onUpdate('cascade');
            $table->decimal('tarifa', 10, 2)->default(0.00);
            $table->integer('anos_experiencia')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['medico_id', 'especialidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medico_especialidad');
    }
};
