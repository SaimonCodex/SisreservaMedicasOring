<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Modify status column to support locked state
            // 0 = inactive, 1 = active, 2 = locked
            $table->tinyInteger('status')->default(1)->change();
            
            // Add lockout fields
            $table->timestamp('blocked_until')->nullable()->after('status');
            $table->text('lock_reason')->nullable()->after('blocked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['blocked_until', 'lock_reason']);
        });
    }
};
