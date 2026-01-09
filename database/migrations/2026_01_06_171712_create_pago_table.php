<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->foreignId('id_factura_paciente')->constrained('facturas_pacientes')->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('id_metodo');
            
            $table->date('fecha_pago');
            $table->decimal('monto_pagado_bs', 20, 2);
            $table->decimal('monto_equivalente_usd', 10, 2);
            $table->foreignId('tasa_aplicada_id')->nullable()->constrained('tasas_dolar')->onUpdate('cascade');
            $table->string('referencia', 255)->nullable();
            $table->text('comentarios')->nullable();
            
            $table->enum('estado', ['Pendiente', 'Confirmado', 'Rechazado', 'Reembolsado'])->default('Pendiente');
            $table->foreignId('confirmado_por')->nullable()->constrained('administradores')->onDelete('set null')->onUpdate('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->foreign('id_metodo')->references('id_metodo')->on('metodo_pago')->onUpdate('cascade');
            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
