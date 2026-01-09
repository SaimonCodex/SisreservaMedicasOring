<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracion_reparto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos');
            $table->foreignId('consultorio_id')->nullable()->constrained('consultorios');
            
            $table->decimal('porcentaje_medico', 5, 2);
            $table->decimal('porcentaje_consultorio', 5, 2);
            $table->decimal('porcentaje_sistema', 5, 2);
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['medico_id', 'consultorio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_reparto');
    }
};
