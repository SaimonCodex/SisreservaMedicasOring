<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_clinica_base', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            
            $table->enum('tipo_sangre', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->text('alergias')->nullable();
            $table->text('alergias_medicamentos')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('antecedentes_personales')->nullable();
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('cirugias_previas')->nullable();
            $table->text('habitos')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique('paciente_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_clinica_base');
    }
};
