<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar campos a pacientes_especiales
        Schema::table('pacientes_especiales', function (Blueprint $table) {
            $table->string('primer_nombre', 100)->nullable()->after('paciente_id');
            $table->string('segundo_nombre', 100)->nullable()->after('primer_nombre');
            $table->string('primer_apellido', 100)->nullable()->after('segundo_nombre');
            $table->string('segundo_apellido', 100)->nullable()->after('primer_apellido');
            $table->enum('tipo_documento', ['V', 'E', 'P', 'J'])->nullable()->after('segundo_apellido');
            $table->string('numero_documento', 30)->nullable()->after('tipo_documento');
            $table->date('fecha_nac')->nullable()->after('numero_documento');
            $table->boolean('tiene_documento')->default(true)->after('fecha_nac');
            
            // Campos de ubicación heredados del representante
            $table->unsignedBigInteger('estado_id')->nullable()->after('tiene_documento');
            $table->unsignedBigInteger('ciudad_id')->nullable()->after('estado_id');
            $table->unsignedBigInteger('municipio_id')->nullable()->after('ciudad_id');
            $table->unsignedBigInteger('parroquia_id')->nullable()->after('municipio_id');
            $table->text('direccion_detallada')->nullable()->after('parroquia_id');
            
            // Foreign keys
            $table->foreign('estado_id')->references('id_estado')->on('estados')->onDelete('set null');
            $table->foreign('ciudad_id')->references('id_ciudad')->on('ciudades')->onDelete('set null');
            $table->foreign('municipio_id')->references('id_municipio')->on('municipios')->onDelete('set null');
            $table->foreign('parroquia_id')->references('id_parroquia')->on('parroquias')->onDelete('set null');
        });

        // 2. Hacer paciente_id nullable en pacientes_especiales (puede no tener paciente asociado inicialmente)
        Schema::table('pacientes_especiales', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->change();
        });

        // 3. Actualizar tipo_consulta en citas - agregar 'Consultorio' al enum
        // Primero necesitamos modificar el enum existente
        DB::statement("ALTER TABLE citas MODIFY COLUMN tipo_consulta ENUM('Presencial', 'Online', 'Domicilio', 'Consultorio') DEFAULT 'Presencial'");
    }

    public function down(): void
    {
        Schema::table('pacientes_especiales', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropForeign(['ciudad_id']);
            $table->dropForeign(['municipio_id']);
            $table->dropForeign(['parroquia_id']);
            
            $table->dropColumn([
                'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido',
                'tipo_documento', 'numero_documento', 'fecha_nac', 'tiene_documento',
                'estado_id', 'ciudad_id', 'municipio_id', 'parroquia_id', 'direccion_detallada'
            ]);
        });

        DB::statement("ALTER TABLE citas MODIFY COLUMN tipo_consulta ENUM('Presencial', 'Online', 'Domicilio') DEFAULT 'Presencial'");
    }
};
