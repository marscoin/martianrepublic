<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationActivity;
use App\Models\OrganizationAttachment;
use App\Models\OrganizationMember;
use App\Models\OrganizationMutation;
use App\Models\OrganizationTask;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrganizationWorkspace
{
    public function activeUser(User $user): void
    {
        abort_if($user->status === 'inactive', 403, 'This account is inactive.');
        abort_unless($user->civicWallet()->exists(), 403, 'Connect your civic identity first.');
    }

    public function member(string $organizationId, int $userId): OrganizationMember
    {
        return OrganizationMember::where('organization_id', $organizationId)
            ->where('user_id', $userId)->where('status', 'active')->firstOrFail();
    }

    public function owner(Organization $organization, int $userId): void
    {
        abort_unless((int) $organization->owner_id === $userId, 403, 'Only the organization owner can do this.');
    }

    /**
     * Lock order is always actor, then organization. All membership and task
     * mutations for one organization share its row lock. Authorization is
     * checked before receipt replay so removal also revokes cached responses.
     */
    public function mutate(User $user, string $operation, array $data, ?string $organizationId, callable $authorize, callable $apply, int $status = 200): JsonResponse
    {
        return DB::transaction(function () use ($user, $operation, $data, $organizationId, $authorize, $apply, $status) {
            $actor = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $this->activeUser($actor);
            $organization = $organizationId === null ? null : Organization::whereKey($organizationId)->lockForUpdate()->firstOrFail();
            $authorize($organization);
            $clientId = $data['client_id'];
            unset($data['client_id']);
            $hash = hash('sha256', json_encode($this->canonical($data), JSON_THROW_ON_ERROR));
            $receipt = OrganizationMutation::where('user_id', $user->id)->where('client_id', $clientId)->first();
            if ($receipt) {
                abort_unless($receipt->operation === $operation && hash_equals($receipt->request_hash, $hash), 409,
                    'This saved request was changed. Refresh before trying again.');

                return response()->json($receipt->response, $receipt->status_code)->header('Cache-Control', 'private, no-store');
            }
            if ($organization) {
                abort_if($organization->archived_at !== null, 409, 'This organization is archived.');
            }
            $response = $apply($organization);
            OrganizationMutation::create([
                'user_id' => $user->id, 'client_id' => $clientId, 'operation' => $operation,
                'request_hash' => $hash, 'response' => $response, 'status_code' => $status, 'created_at' => now(),
            ]);

            return response()->json($response, $status)->header('Cache-Control', 'private, no-store');
        }, 3);
    }

    private function canonical(array $data): array
    {
        if (! array_is_list($data)) {
            ksort($data);
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->canonical($value);
            }
        }

        return $data;
    }

    public function person(?User $user): array
    {
        return [
            'id' => $user?->id,
            'name' => $user?->citizen?->displayname ?: ($user?->fullname ?: 'Former member'),
            'address' => $user?->civicWallet?->public_addr,
            'avatar' => $user?->citizen?->avatar_link,
            'first_name' => $user?->citizen?->firstname, 'last_name' => $user?->citizen?->lastname,
            'registered_at' => $user?->citizen?->created_at?->toISOString(),
        ];
    }

    public function organization(Organization $organization, string $role = 'member'): array
    {
        return [
            'id' => $organization->id, 'name' => $organization->name,
            'description' => $organization->description, 'owner_id' => (int) $organization->owner_id, 'role' => $role,
            'archived_at' => $organization->archived_at?->toISOString(),
            'member_count' => $organization->members()->where('status', 'active')->count(),
            'open_task_count' => $organization->tasks()->where('status', 'open')->count(),
            'created_at' => $organization->created_at->toISOString(),
        ];
    }

    public function task(OrganizationTask $task): array
    {
        return [
            'id' => $task->id, 'organization_id' => $task->organization_id, 'title' => $task->title,
            'details' => $task->details, 'created_by' => (int) $task->created_by,
            'assigned_to' => $task->assigned_to === null ? null : (int) $task->assigned_to,
            'due_date' => $task->due_date?->format('Y-m-d'), 'status' => $task->status, 'version' => (int) $task->version,
            'completed_at' => $task->completed_at?->toISOString(), 'created_at' => $task->created_at->toISOString(),
            'updated_at' => $task->updated_at->toISOString(),
        ];
    }

    public function activity(OrganizationActivity $activity): array
    {
        return [
            'id' => $activity->id, 'organization_id' => $activity->organization_id, 'task_id' => $activity->task_id,
            'kind' => $activity->kind, 'body' => $activity->body, 'metadata' => $activity->metadata,
            'actor' => $this->person($activity->actor), 'created_at' => $activity->created_at->toISOString(),
            'attachments' => $activity->attachments->map(fn ($attachment) => $this->attachment($attachment))->values()->all(),
        ];
    }

    public function attachment(OrganizationAttachment $attachment): array
    {
        return [
            'id' => $attachment->id, 'mime' => $attachment->mime, 'size' => $attachment->size,
            'url' => '/api/organizations/'.$attachment->organization_id.'/attachments/'.$attachment->id,
        ];
    }

    public function record(string $organizationId, int $actorId, string $kind, string $body, ?string $taskId = null, array $metadata = [], array $attachmentIds = []): OrganizationActivity
    {
        $attachments = OrganizationAttachment::where('organization_id', $organizationId)
            ->where('uploaded_by', $actorId)->whereNull('activity_id')->whereIn('id', $attachmentIds)->get();
        abort_unless($attachments->count() === count($attachmentIds), 422, 'A photo is unavailable or already belongs to another record.');
        $activity = OrganizationActivity::create([
            'organization_id' => $organizationId, 'actor_id' => $actorId, 'kind' => $kind,
            'body' => $body, 'task_id' => $taskId, 'metadata' => $metadata, 'created_at' => now(),
        ]);
        foreach ($attachments as $attachment) {
            $attachment->activity_id = $activity->id;
            $attachment->save();
        }

        return $activity;
    }

    public function validateAssignee(string $organizationId, ?int $userId): void
    {
        if ($userId !== null) {
            abort_unless(OrganizationMember::where('organization_id', $organizationId)->where('user_id', $userId)
                ->where('status', 'active')->whereHas('user', fn ($query) => $query->where(fn ($q) => $q->whereNull('status')->orWhere('status', '!=', 'inactive')))->exists(),
                422, 'Assign tasks to an active organization member.');
        }
    }

    public function checkVersion(OrganizationTask $task, int $version): void
    {
        if ((int) $task->version !== $version) {
            throw new HttpResponseException(response()->json([
                'message' => 'This task changed while you were working. Refresh and review the latest version.',
                'code' => 'version_conflict', 'task' => $this->task($task),
            ], 409));
        }
    }
}
