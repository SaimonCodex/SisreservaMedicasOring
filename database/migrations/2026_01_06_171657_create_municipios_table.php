<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id('id_municipio');
            $table->unsignedBigInteger('id_estado');
            $table->string('municipio', 100);
            
            $table->foreign('id_estado')->references('id_estado')->on('estados')
                  ->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['municipio', 'id_estado']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
