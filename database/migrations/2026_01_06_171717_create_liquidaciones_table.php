<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('entidad_tipo', ['Medico', 'Consultorio']);
            $table->unsignedBigInteger('entidad_id');
            
            $table->decimal('monto_total_usd', 15, 2);
            $table->decimal('monto_total_bs', 20, 2);
            
            $table->enum('metodo_pago', ['Transferencia', 'Zelle', 'Efectivo', 'Pago Movil', 'Otro']);
            $table->string('referencia', 100);
            $table->date('fecha_pago');
            $table->text('observaciones')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
