<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('representante_paciente_especial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('representante_id')->constrained('representantes')->onUpdate('cascade');
            $table->foreignId('paciente_especial_id')->constrained('pacientes_especiales')->onUpdate('cascade');
            $table->enum('tipo_responsabilidad', ['Principal', 'Suplente', 'Emergencia'])->default('Principal');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['representante_id', 'paciente_especial_id'], 'rep_pac_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representante_paciente_especial');
    }
};
