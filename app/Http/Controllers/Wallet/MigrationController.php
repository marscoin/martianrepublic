<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\HDWallet;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrationController extends Controller
{
    /**
     * Show the civic wallet migration page.
     */
    public function show()
    {
        if (! Auth::check()) {
            return redirect('/login');
        }

        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->first();

        if (! $profile) {
            return redirect('/twofa');
        }
        if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
            return redirect('/twofachallenge');
        }

        $civic = CivicWallet::where('user_id', $uid)->first();
        $citizen = Citizen::where('userid', $uid)->first();

        // Get migration history
        $migrations = DB::table('civic_wallet_migrations')
            ->where('user_id', $uid)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = json_decode(file_get_contents('/home/mars/constitution/marswallet.json'), true);

        return view('wallet.migrate', [
            'civic_wallet' => $civic,
            'citizen' => $citizen,
            'migrations' => $migrations,
            'SALT' => $data['salt'],
            'iv' => $data['iv'],
            'wallet_open' => $profile->civic_wallet_open,
        ]);
    }

    /**
     * Step 1: Register the migration intent.
     * Client will then sign a tx from the old address with OP_RETURN announcing the new address.
     */
    public function initiate(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'old_address' => 'required|string|max:50',
            'new_address' => 'required|string|max:50',
        ]);

        $uid = Auth::user()->id;
        $oldAddr = $request->input('old_address');
        $newAddr = $request->input('new_address');

        // Verify old address belongs to this user
        $civic = CivicWallet::where('user_id', $uid)->where('public_addr', $oldAddr)->first();
        if (! $civic) {
            return response()->json(['error' => 'Old civic wallet not found for this user'], 404);
        }

        // Check new address isn't already claimed
        $existing = CivicWallet::where('public_addr', $newAddr)->first();
        if ($existing && $existing->user_id !== $uid) {
            return response()->json(['error' => 'New address already belongs to another user'], 409);
        }

        // Create pending migration record
        $migrationId = DB::table('civic_wallet_migrations')->insertGetId([
            'user_id' => $uid,
            'old_address' => $oldAddr,
            'new_address' => $newAddr,
            'old_encrypted_seed' => $civic->encrypted_seed,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("Civic wallet migration initiated for user {$uid}: {$oldAddr} → {$newAddr} (migration #{$migrationId})");

        return response()->json([
            'success' => true,
            'migration_id' => $migrationId,
            'message' => 'Migration initiated. Sign a transaction from your old address to confirm.',
            'op_return_data' => 'MIGRATE:' . $newAddr,
        ]);
    }

    /**
     * Step 2: Confirm the migration with the broadcast txid.
     * The client has signed and broadcast a tx from old_address with OP_RETURN "MIGRATE:<new_address>".
     */
    public function confirm(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'migration_id' => 'required|integer',
            'txid' => 'required|string|max:200',
            'encrypted_seed' => 'required|string',
        ]);

        $uid = Auth::user()->id;
        $migrationId = $request->input('migration_id');
        $txid = $request->input('txid');
        $encryptedSeed = $request->input('encrypted_seed');

        $migration = DB::table('civic_wallet_migrations')
            ->where('id', $migrationId)
            ->where('user_id', $uid)
            ->where('status', 'pending')
            ->first();

        if (! $migration) {
            return response()->json(['error' => 'Pending migration not found'], 404);
        }

        try {
            DB::beginTransaction();

            // 1. Update migration record with txid
            DB::table('civic_wallet_migrations')
                ->where('id', $migrationId)
                ->update([
                    'migration_txid' => $txid,
                    'status' => 'confirmed',
                    'confirmed_at' => now(),
                    'updated_at' => now(),
                ]);

            // 2. Preserve old civic wallet as HD wallet (funds remain accessible)
            $oldHd = HDWallet::where('user_id', $uid)
                ->where('public_addr', $migration->old_address)
                ->first();

            if (! $oldHd) {
                HDWallet::create([
                    'user_id' => $uid,
                    'wallet_type' => 'Migrated',
                    'public_addr' => $migration->old_address,
                    'encrypted_seed' => $migration->old_encrypted_seed,
                ]);
            }

            // 3. Update civic wallet to new address + seed
            $civic = CivicWallet::where('user_id', $uid)->first();
            if ($civic) {
                $civic->public_addr = $migration->new_address;
                $civic->encrypted_seed = $encryptedSeed;
                $civic->save();
            }

            // 4. Update citizen record
            $citizen = Citizen::where('userid', $uid)->first();
            if ($citizen) {
                $citizen->public_address = $migration->new_address;
                $citizen->save();
            }

            // 5. Reset wallet session
            $profile = Profile::where('userid', $uid)->first();
            if ($profile) {
                $profile->civic_wallet_open = 0;
                $profile->wallet_open = 0;
                $profile->save();
            }

            DB::commit();

            Log::info("Civic wallet migration confirmed for user {$uid}: {$migration->old_address} → {$migration->new_address} (txid: {$txid})");

            return response()->json([
                'success' => true,
                'old_address' => $migration->old_address,
                'new_address' => $migration->new_address,
                'txid' => $txid,
                'message' => 'Civic wallet migrated successfully. Old wallet preserved as HD wallet. Please reconnect to use your new civic identity.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Civic wallet migration failed for user {$uid}: " . $e->getMessage());

            return response()->json(['error' => 'Migration failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get migration history for the current user.
     */
    public function history()
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $migrations = DB::table('civic_wallet_migrations')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($migrations);
    }
}
