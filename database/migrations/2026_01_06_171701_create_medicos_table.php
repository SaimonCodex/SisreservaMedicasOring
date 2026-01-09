<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('usuarios')->onDelete('cascade');
            
            $table->string('primer_nombre', 100);
            $table->string('segundo_nombre', 100)->nullable();
            $table->string('primer_apellido', 100);
            $table->string('segundo_apellido', 100)->nullable();
            $table->enum('tipo_documento', ['V', 'E', 'P', 'J'])->nullable();
            $table->string('numero_documento', 20)->nullable();
            $table->date('fecha_nac')->nullable();
            
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->unsignedBigInteger('municipio_id')->nullable();
            $table->unsignedBigInteger('parroquia_id')->nullable();
            $table->text('direccion_detallada')->nullable();
            
            $table->enum('prefijo_tlf', ['+58', '+57', '+1', '+34'])->nullable();
            $table->string('numero_tlf', 15)->nullable();
            $table->string('genero', 20)->nullable();
            
            $table->string('nro_colegiatura', 50)->nullable();
            $table->text('formacion_academica')->nullable();
            $table->text('experiencia_profesional')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->foreign('estado_id')->references('id_estado')->on('estados')->onDelete('set null');
            $table->foreign('ciudad_id')->references('id_ciudad')->on('ciudades')->onDelete('set null');
            $table->foreign('municipio_id')->references('id_municipio')->on('municipios')->onDelete('set null');
            $table->foreign('parroquia_id')->references('id_parroquia')->on('parroquias')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};
