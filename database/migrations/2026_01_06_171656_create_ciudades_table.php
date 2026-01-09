<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id('id_ciudad');
            $table->unsignedBigInteger('id_estado');
            $table->string('ciudad', 200);
            $table->boolean('capital')->default(false);
            
            $table->foreign('id_estado')->references('id_estado')->on('estados')
                  ->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['ciudad', 'id_estado']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};
