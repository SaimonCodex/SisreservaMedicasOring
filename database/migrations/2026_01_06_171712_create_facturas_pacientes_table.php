<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas_pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->unique()->constrained('citas')->onDelete('restrict')->onUpdate('cascade');
            
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('restrict')->onUpdate('cascade');
            
            $table->decimal('monto_usd', 10, 2);
            $table->foreignId('tasa_id')->constrained('tasas_dolar')->onDelete('restrict')->onUpdate('cascade');
            $table->decimal('monto_bs', 20, 2);
            
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->string('numero_factura', 50)->unique()->nullable();
            $table->enum('status_factura', ['Emitida', 'Pagada', 'Anulada', 'Vencida'])->default('Emitida');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index('fecha_emision');
            $table->index('numero_factura');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas_pacientes');
    }
};
