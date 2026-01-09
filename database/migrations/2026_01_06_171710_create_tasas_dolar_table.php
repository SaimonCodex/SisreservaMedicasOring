<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasas_dolar', function (Blueprint $table) {
            $table->id();
            $table->enum('fuente', ['BCV', 'MonitorDolar', 'Paralelo', 'Oficial'])->default('BCV');
            $table->decimal('valor', 12, 4);
            $table->date('fecha_tasa');
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('fecha_tasa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasas_dolar');
    }
};
