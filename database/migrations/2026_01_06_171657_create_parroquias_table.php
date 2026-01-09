<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parroquias', function (Blueprint $table) {
            $table->id('id_parroquia');
            $table->unsignedBigInteger('id_municipio');
            $table->string('parroquia', 250);
            
            $table->foreign('id_municipio')->references('id_municipio')->on('municipios')
                  ->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['parroquia', 'id_municipio']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parroquias');
    }
};
