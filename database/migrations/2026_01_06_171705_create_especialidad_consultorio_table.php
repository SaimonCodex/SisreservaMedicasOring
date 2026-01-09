<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('especialidad_consultorio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especialidad_id')->constrained('especialidades')->onUpdate('cascade');
            $table->foreignId('consultorio_id')->constrained('consultorios')->onUpdate('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->unique(['especialidad_id', 'consultorio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('especialidad_consultorio');
    }
};
