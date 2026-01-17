<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias_historia_base', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_clinica_base_id')->constrained('historia_clinica_base')->onDelete('cascade');
            $table->foreignId('medico_id')->constrained('medicos');
            
            $table->string('tipo_accion', 50); // CREACION, EDICION
            $table->string('campo_modificado', 100)->nullable();
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->string('motivo_cambio', 255)->nullable();
            
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            
            $table->timestamps();
            
            $table->index(['historia_clinica_base_id', 'created_at'], 'aud_hist_base_created_idx');
            $table->index('medico_id', 'aud_hist_medico_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditorias_historia_base');
    }
};
