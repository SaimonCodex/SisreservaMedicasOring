<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->string('foto_perfil')->nullable()->after('genero');
            $table->string('banner_perfil')->nullable()->after('foto_perfil');
            $table->string('banner_color')->nullable()->after('banner_perfil');
            $table->boolean('tema_dinamico')->default(false)->after('banner_color');
        });
    }

    public function down(): void
    {
        Schema::table('administradores', function (Blueprint $table) {
            $table->dropColumn(['foto_perfil', 'banner_perfil', 'banner_color', 'tema_dinamico']);
        });
    }
};
