<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_historial', function (Blueprint $table) {
            $table->foreignId('evolucion_id')->nullable()->after('paciente_id')->constrained('evolucion_clinica')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_historial', function (Blueprint $table) {
            $table->dropForeign(['evolucion_id']);
            $table->dropColumn('evolucion_id');
        });
    }
};
