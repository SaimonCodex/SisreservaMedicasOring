<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('especialidades', function (Blueprint $table) {
            $table->string('codigo', 50)->nullable()->after('nombre');
            $table->integer('duracion_cita_default')->default(30)->after('descripcion');
            $table->string('color', 50)->default('medical')->after('duracion_cita_default');
            $table->string('icono', 50)->default('heart-pulse')->after('color');
            $table->integer('prioridad')->default(2)->after('icono');
            $table->text('requisitos')->nullable()->after('prioridad');
            $table->text('observaciones')->nullable()->after('requisitos');
        });
    }

    public function down(): void
    {
        Schema::table('especialidades', function (Blueprint $table) {
            $table->dropColumn([
                'codigo',
                'duracion_cita_default',
                'color',
                'icono',
                'prioridad',
                'requisitos',
                'observaciones'
            ]);
        });
    }
};
