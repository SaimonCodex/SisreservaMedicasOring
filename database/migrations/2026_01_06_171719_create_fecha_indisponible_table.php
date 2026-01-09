<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fecha_indisponible', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos');
            $table->foreignId('consultorio_id')->nullable()->constrained('consultorios');
            $table->date('fecha');
            $table->string('motivo', 255)->nullable();
            $table->boolean('todo_el_dia')->default(true);
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['medico_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fecha_indisponible');
    }
};
