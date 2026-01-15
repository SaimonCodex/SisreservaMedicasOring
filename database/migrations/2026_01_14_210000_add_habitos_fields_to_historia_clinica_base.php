<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historia_clinica_base', function (Blueprint $table) {
            $table->string('habito_tabaco')->nullable()->after('habitos');
            $table->string('habito_alcohol')->nullable()->after('habito_tabaco');
            $table->string('actividad_fisica')->nullable()->after('habito_alcohol');
            $table->string('dieta')->nullable()->after('actividad_fisica');
        });
    }

    public function down(): void
    {
        Schema::table('historia_clinica_base', function (Blueprint $table) {
            $table->dropColumn(['habito_tabaco', 'habito_alcohol', 'actividad_fisica', 'dieta']);
        });
    }
};
