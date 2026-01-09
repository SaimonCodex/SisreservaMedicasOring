<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultorios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('ciudad_id');
            $table->unsignedBigInteger('municipio_id')->nullable();
            $table->unsignedBigInteger('parroquia_id')->nullable();
            $table->text('direccion_detallada')->nullable();
            
            $table->string('telefono', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->time('horario_inicio')->nullable();
            $table->time('horario_fin')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->foreign('estado_id')->references('id_estado')->on('estados')->onDelete('restrict');
            $table->foreign('ciudad_id')->references('id_ciudad')->on('ciudades')->onDelete('restrict');
            $table->foreign('municipio_id')->references('id_municipio')->on('municipios')->onDelete('set null');
            $table->foreign('parroquia_id')->references('id_parroquia')->on('parroquias')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultorios');
    }
};
