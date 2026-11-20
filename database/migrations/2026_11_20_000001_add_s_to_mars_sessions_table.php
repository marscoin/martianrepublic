<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The QR login flow (AuthApiController::marsAuth) records the Mars address
     * that signed the session challenge into mars_sessions.s, and checkAuth /
     * wauth read it. The original create_mars_sessions_table migration never
     * defined that column, so on databases built from migrations the write
     * fails with "Unknown column 's'" and QR sign-in 500s. Add it (nullable;
     * populated only once a session completes its challenge-response).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('mars_sessions', 's')) {
            Schema::table('mars_sessions', function (Blueprint $table) {
                $table->string('s')->nullable()->after('v');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mars_sessions', 's')) {
            Schema::table('mars_sessions', function (Blueprint $table) {
                $table->dropColumn('s');
            });
        }
    }
};
