<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_totales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabecera_id')->constrained('factura_cabecera')->onDelete('cascade');
            
            $table->enum('entidad_tipo', ['Paciente', 'Medico', 'Consultorio', 'Sistema']);
            $table->unsignedBigInteger('entidad_id')->nullable();
            
            $table->decimal('base_imponible_usd', 15, 2);
            $table->decimal('impuestos_usd', 15, 2)->default(0.00);
            $table->decimal('total_final_usd', 15, 2);
            
            $table->decimal('total_final_bs', 20, 2);
            
            $table->enum('estado_liquidacion', ['Pendiente', 'Liquidado', 'Retenido', 'No Aplica'])->default('Pendiente');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_totales');
    }
};
