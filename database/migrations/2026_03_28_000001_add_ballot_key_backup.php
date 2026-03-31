<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ballots', function (Blueprint $table) {
            $table->text('encrypted_key')->nullable()->after('status');
            $table->string('encryption_iv', 64)->nullable()->after('encrypted_key');
            $table->string('ballot_txid', 128)->nullable()->after('encryption_iv');
            $table->timestamp('confirmed_at')->nullable()->after('ballot_txid');
            $table->boolean('notified')->default(false)->after('confirmed_at');
            $table->string('hidden_target', 64)->nullable()->after('notified');
        });
    }

    public function down(): void
    {
        Schema::table('ballots', function (Blueprint $table) {
            $table->dropColumn([
                'encrypted_key', 'encryption_iv', 'ballot_txid',
                'confirmed_at', 'notified', 'hidden_target',
            ]);
        });
    }
};
