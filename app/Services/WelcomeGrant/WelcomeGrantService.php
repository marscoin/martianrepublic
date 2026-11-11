<?php

namespace App\Services\WelcomeGrant;

use App\Includes\MarscoinECDSA;
use App\Models\Citizen;
use App\Models\CivicWallet;
use App\Models\Profile;
use App\Models\User;
use App\Models\WelcomeGrant;
use App\Models\WelcomeGrantNonce;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * One small payment per person, to their own civic address, proven by a
 * signature from that address over a single-use server nonce.
 */
class WelcomeGrantService
{
    // No configuration can make a single grant larger than 1 MARS.
    private const MAX_AMOUNT_ZUBRINS = 100000000;

    public function __construct(private GrantSender $sender) {}

    public function amount(): int
    {
        return max(1, min((int) config('welcome_grant.amount_zubrins', 1000000), self::MAX_AMOUNT_ZUBRINS));
    }

    public function message(string $address, string $nonce): string
    {
        return "Martian Republic welcome grant\naddress: {$address}\nnonce: {$nonce}";
    }

    public function civicAddress(User $user): ?string
    {
        $address = CivicWallet::where('user_id', $user->id)->orderBy('id')->value('public_addr');

        return is_string($address) && $address !== '' ? $address : null;
    }

    public function challenge(User $user, ?string $ip): array
    {
        $address = $this->civicAddress($user);
        $answer = fn (string $status, ?string $reason, array $extra = []) => array_merge([
            'eligible' => $status === 'available', 'reason' => $reason, 'status' => $status,
            'address' => (string) $address, 'amount_zubrins' => $this->amount(),
            'nonce' => null, 'message' => null, 'expires_at' => null, 'txid' => null,
        ], $extra);

        $grant = WelcomeGrant::where('user_id', $user->id)->first();
        if ($grant && $grant->status === 'sent') {
            return $answer('already_granted', 'You have already received the welcome grant.', ['txid' => $grant->txid, 'amount_zubrins' => $grant->amount_zubrins]);
        }
        if ($grant && $grant->status !== 'failed') {
            return $answer('unavailable', 'A grant for this account is already being processed.');
        }
        if (! config('welcome_grant.enabled')) {
            return $answer('unavailable', 'The welcome grant is not offered at the moment.');
        }
        if ($reason = $this->ineligibility($user, $address)) {
            return $answer('not_eligible', $reason);
        }
        if ($reason = $this->shortage($this->ipHash($ip))) {
            return $answer('unavailable', $reason);
        }

        // Only the newest challenge of a user is valid.
        WelcomeGrantNonce::where('user_id', $user->id)->orWhere('expires_at', '<', now()->subDay())->delete();
        $nonce = WelcomeGrantNonce::create([
            'user_id' => $user->id, 'address' => $address, 'nonce' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addMinutes((int) config('welcome_grant.nonce_ttl_minutes', 10)),
        ]);

        return $answer('available', null, [
            'nonce' => $nonce->nonce, 'message' => $this->message($address, $nonce->nonce),
            'expires_at' => $nonce->expires_at->toIso8601String(),
        ]);
    }

    public function claim(User $user, mixed $address, mixed $nonce, mixed $signature, ?string $ip): array
    {
        $answer = fn (string $status, ?string $reason = null, ?string $txid = null, ?int $amount = null) => [
            'status' => $status, 'txid' => $txid, 'amount_zubrins' => $amount, 'reason' => $reason,
        ];

        $grant = WelcomeGrant::where('user_id', $user->id)->first();
        if ($grant && $grant->status === 'sent') {
            return $answer('already_granted', 'You have already received the welcome grant.', $grant->txid, $grant->amount_zubrins);
        }
        if ($grant && $grant->status !== 'failed') {
            return $answer('unavailable', 'A grant for this account is already being processed.');
        }
        if (! config('welcome_grant.enabled')) {
            return $answer('unavailable', 'The welcome grant is not offered at the moment.');
        }
        if (! is_string($address) || ! is_string($nonce) || ! is_string($signature)
            || strlen($nonce) > 64 || strlen($signature) > 128 || $signature === '') {
            return $answer('invalid_signature', 'An address, a nonce and a signature are required.');
        }
        // Paid only to the signed-in user's own civic address on record.
        $civicAddress = $this->civicAddress($user);
        if ($civicAddress === null || ! hash_equals($civicAddress, $address)) {
            return $answer('not_eligible', 'The address is not the civic address of this account.');
        }

        $challenge = WelcomeGrantNonce::where('nonce', $nonce)->where('user_id', $user->id)->where('address', $civicAddress)->first();
        if (! $challenge || $challenge->used_at !== null || $challenge->expires_at->isPast()) {
            return $answer('expired', 'This request has expired or was already used. Please ask for a new one.');
        }
        // Same check as the sign-in at /api/token.
        try {
            $valid = (new MarscoinECDSA)->checkSignatureForMessage($civicAddress, $signature, $this->message($civicAddress, $challenge->nonce));
        } catch (\Throwable) {
            $valid = false;
        }
        if (! $valid) {
            return $answer('invalid_signature', 'The signature does not belong to this civic address.');
        }
        // Single use: only one request can turn used_at from null to a time.
        if (WelcomeGrantNonce::where('id', $challenge->id)->whereNull('used_at')->update(['used_at' => now()]) !== 1) {
            return $answer('expired', 'This request has expired or was already used. Please ask for a new one.');
        }
        if ($reason = $this->ineligibility($user, $civicAddress)) {
            return $answer('not_eligible', $reason);
        }

        $amount = $this->amount();
        $ipHash = $this->ipHash($ip);
        // Limits are checked and the grant row is written one request at a
        // time, so the daily caps cannot be overrun by simultaneous claims.
        $lock = Cache::lock('welcome-grant-reserve', 30);
        if (! $lock->get()) {
            return $answer('unavailable', 'The welcome grant is busy. Please try again in a moment.');
        }
        try {
            if ($reason = $this->shortage($ipHash)) {
                return $answer('unavailable', $reason);
            }
            $grant = $this->reserve($user->id, $civicAddress, $amount, $ipHash);
        } finally {
            $lock->release();
        }
        if (! $grant) {
            $existing = WelcomeGrant::where('user_id', $user->id)->orWhere('address', $civicAddress)->first();

            return $existing && $existing->status === 'sent' && $existing->user_id === $user->id
                ? $answer('already_granted', 'You have already received the welcome grant.', $existing->txid, $existing->amount_zubrins)
                : $answer('unavailable', 'A grant for this account or address is already being processed.');
        }

        // The row above is committed. From here on a second claim cannot pay.
        try {
            $txid = $this->sender->send($civicAddress, $amount);
        } catch (\Throwable $error) {
            $nothingSent = $error instanceof GrantSendException && $error->nothingSent;
            $grant->update(['status' => $nothingSent ? 'failed' : 'uncertain', 'last_error' => substr($error->getMessage(), 0, 255)]);
            Log::error('Welcome grant was not sent', ['grant' => $grant->id, 'user' => $user->id, 'state' => $grant->status, 'error' => $grant->last_error]);

            return $answer('unavailable', $nothingSent
                ? 'The grant could not be sent right now. Please try again later.'
                : 'The grant could not be confirmed. It will be checked before anything is sent again.');
        }
        $grant->update(['status' => 'sent', 'txid' => $txid, 'sent_at' => now(), 'last_error' => null]);
        Log::info('Welcome grant sent', ['grant' => $grant->id, 'user' => $user->id, 'txid' => $txid]);

        return $answer('sent', null, $txid, $amount);
    }

    public function status(User $user): array
    {
        $grant = WelcomeGrant::where('user_id', $user->id)->where('status', 'sent')->first();

        return [
            'granted' => (bool) $grant, 'txid' => $grant?->txid, 'amount_zubrins' => $grant?->amount_zubrins,
            'granted_at' => $grant?->sent_at?->toIso8601String(),
        ];
    }

    /**
     * Claim the single grant row of this user and address. Returns null when
     * another claim holds it. The unique keys on user_id and address decide
     * between simultaneous claims; a row whose earlier send certainly failed
     * is taken over by exactly one retry.
     */
    public function reserve(int $userId, string $address, int $amount, ?string $ipHash): ?WelcomeGrant
    {
        $now = now();
        $retry = WelcomeGrant::where('user_id', $userId)->where('address', $address)->where('status', 'failed')
            ->update(['status' => 'pending', 'amount_zubrins' => $amount, 'ip_hash' => $ipHash, 'attempted_at' => $now,
                'attempts' => DB::raw('attempts + 1'), 'updated_at' => $now]);
        if ($retry === 1) {
            return WelcomeGrant::where('user_id', $userId)->first();
        }
        try {
            return WelcomeGrant::create([
                'user_id' => $userId, 'address' => $address, 'amount_zubrins' => $amount, 'status' => 'pending',
                'ip_hash' => $ipHash, 'attempted_at' => $now,
            ]);
        } catch (UniqueConstraintViolationException) {
            return null;
        }
    }

    /**
     * Why this user may not have the grant, or null. Steps 1 and 2 of the
     * registration must be complete: names from /api/scitizen, photo from
     * /api/pinpic and video from /api/pinvideo, all kept on the citizen row.
     */
    public function ineligibility(User $user, ?string $address): ?string
    {
        if ($address === null) {
            return 'This account has no civic address.';
        }
        if ($user->status === 'inactive') {
            return 'This account is not active.';
        }
        $profile = Profile::where('userid', $user->id)->first();
        if ($profile && ($profile->general_public || $profile->citizen)) {
            return 'Your application is already published, so the grant is not needed.';
        }
        $citizen = Citizen::where('userid', $user->id)->first();
        $filled = fn (string $field) => $citizen && trim((string) $citizen->$field) !== '';
        if (! $filled('firstname') || ! $filled('lastname')) {
            return 'Please enter your name first.';
        }
        if (! $filled('avatar_link')) {
            return 'Please add your photo first.';
        }
        if (! $filled('liveness_link')) {
            return 'Please record your video first.';
        }
        if (WelcomeGrant::where('address', $address)->where('user_id', '!=', $user->id)->exists()) {
            return 'A grant was already requested for this address.';
        }

        return null;
    }

    /**
     * Why no grant can be paid right now (daily limits, funds), or null.
     */
    private function shortage(?string $ipHash): ?string
    {
        $today = WelcomeGrant::where('attempted_at', '>=', now()->startOfDay())->where('status', '!=', 'failed');
        if ((clone $today)->count() >= (int) config('welcome_grant.daily_cap', 50)) {
            return 'All welcome grants for today have been given. Please try again tomorrow.';
        }
        if ($ipHash === null || (clone $today)->where('ip_hash', $ipHash)->count() >= (int) config('welcome_grant.per_ip_daily_cap', 3)) {
            return 'Too many welcome grants were requested from this network today. Please try again tomorrow.';
        }
        $balance = $this->sender->availableZubrins();
        if ($balance === null) {
            return 'The welcome grant cannot be paid at the moment.';
        }
        if ($balance < $this->amount() + (int) config('welcome_grant.fee_reserve_zubrins', 100000)) {
            return 'The welcome grant fund is empty at the moment.';
        }

        return null;
    }

    private function ipHash(?string $ip): ?string
    {
        // Only needed to count per network; the address itself is not kept.
        return $ip ? hash_hmac('sha256', $ip, (string) config('app.key')) : null;
    }
}
