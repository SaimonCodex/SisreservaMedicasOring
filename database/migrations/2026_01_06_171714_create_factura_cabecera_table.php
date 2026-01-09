<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_cabecera', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas');
            $table->string('nro_control', 50)->unique();
            
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('medico_id')->constrained('medicos');
            
            $table->foreignId('tasa_id')->constrained('tasas_dolar');
            $table->timestamp('fecha_emision')->useCurrent();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('fecha_emision');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_cabecera');
    }
};
