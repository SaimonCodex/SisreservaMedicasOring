<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes_especiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('restrict')->onUpdate('cascade');
            $table->enum('tipo', ['Menor de Edad', 'Discapacitado', 'Anciano', 'Incapacitado']);
            $table->text('observaciones')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['paciente_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes_especiales');
    }
};
