<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function blockUser(Request $request, $id)
    {
        Log::debug('in function');
        $uid = Auth::user()->id;
        Log::debug('auth happened');
        $blockedUserId = $id;

        // Insert into `user_blocks` table if not already blocked
        DB::table('user_blocks')->updateOrInsert(
            ['user_id' => $uid, 'blocked_user_id' => $blockedUserId]
        );

        return response()->json(['message' => 'User blocked successfully'], 201);
    }

    public function deleteUser(Request $request, $id)
    {
        try {
            // Users can only deactivate their own account
            $authUser = Auth::user();
            if ((int) $authUser->id !== (int) $id) {
                return response()->json(['message' => 'Unauthorized: you can only delete your own account.'], 403);
            }

            $user = User::findOrFail($id);

            Log::debug('Deleting... '.$id);

            $user->update([
                'status' => 'inactive',
            ]);
            Log::debug('Status updated');

            // Revoke all tokens
            if (method_exists($user, 'tokens')) {
                $user->tokens()->delete();
            }
            Log::debug('Token wiped...');

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the user',
            ], 500);
        }
    }

    public function handleEula(Request $request)
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->firstOrFail();

        return response()->json([
            'is_signed' => (bool) $profile->signed_eula,
        ]);
    }

    public function setEula(Request $request)
    {
        $uid = Auth::user()->id;
        $profile = Profile::where('userid', $uid)->firstOrFail();
        if (! $profile->signed_eula) {
            $profile->signed_eula = 1;
            $profile->save();
        }

        return response()->json([
            'message' => 'EULA signed successfully',
            'is_signed' => true,
        ]);
    }
}
