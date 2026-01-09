<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receptor_id');
            $table->enum('receptor_rol', ['Paciente', 'Medico', 'Admin', 'Root']);
            
            $table->enum('tipo', ['Recordatorio_Cita', 'Pago_Aprobado', 'Pago_Rechazado', 'Cancelacion', 'Alerta_Adm', 'Sistema']);
            $table->string('titulo', 150);
            $table->text('mensaje');
            
            $table->enum('via', ['Correo', 'Sistema', 'WhatsApp', 'SMS', 'Multiple']);
            $table->enum('estado_envio', ['Pendiente', 'Enviado', 'Fallido', 'Leido'])->default('Pendiente');
            $table->text('error_detalle')->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            $table->index(['receptor_id', 'receptor_rol', 'estado_envio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
