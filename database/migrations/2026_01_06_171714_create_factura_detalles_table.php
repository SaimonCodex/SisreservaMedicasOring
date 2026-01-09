<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabecera_id')->constrained('factura_cabecera')->onDelete('cascade');
            
            $table->enum('entidad_tipo', ['Paciente', 'Medico', 'Consultorio', 'Sistema']);
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('descripcion', 255);
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario_usd', 15, 2);
            $table->decimal('subtotal_usd', 15, 2);
            
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
