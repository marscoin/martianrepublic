<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dynamic quorum configuration.
     * Active_N = ema_factor * Active_(N-1) + (1 - ema_factor) * Actual_Participation
     * Active citizens = those who voted or endorsed within active_window_sols.
     */
    public function up(): void
    {
        Schema::create('governance_config', function (Blueprint $table) {
            $table->id();
            $table->decimal('active_citizen_count', 10, 2)->default(0);
            $table->unsignedInteger('last_vote_participation')->default(0);
            $table->decimal('ema_factor', 3, 2)->default(0.80);
            $table->unsignedInteger('active_window_sols')->default(180);
            $table->timestamps();
        });

        // Seed with initial values based on current citizen count
        $citizenCount = DB::table('feed')->where('tag', 'CT')->distinct('userid')->count('userid');
        DB::table('governance_config')->insert([
            'active_citizen_count' => $citizenCount ?: 1,
            'last_vote_participation' => 0,
            'ema_factor' => 0.80,
            'active_window_sols' => 180,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_config');
    }
};
