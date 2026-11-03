<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CivicWallet;
use App\Models\Organization;
use App\Models\OrganizationActivity;
use App\Models\OrganizationAttachment;
use App\Models\OrganizationMember;
use App\Models\OrganizationTask;
use App\Models\User;
use App\Services\OrganizationWorkspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrganizationApiController extends Controller
{
    public function __construct(private OrganizationWorkspace $workspace) {}

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 401);
        $this->workspace->activeUser($user);

        return $user;
    }

    private function read(array $data): JsonResponse
    {
        return response()->json($data)->header('Cache-Control', 'private, no-store');
    }

    public function home(Request $request): JsonResponse
    {
        $user = $this->user($request);
        $memberships = OrganizationMember::with('organization')->where('user_id', $user->id)
            ->where('status', 'active')->whereHas('organization', fn ($q) => $q->whereNull('archived_at'))
            ->orderByDesc('updated_at')->limit(100)->get();
        $invites = OrganizationMember::with('organization')->where('user_id', $user->id)->where('status', 'invited')
            ->whereHas('organization', fn ($q) => $q->whereNull('archived_at'))->orderByDesc('updated_at')->limit(100)->get();
        $tasks = OrganizationTask::whereIn('organization_id', $memberships->pluck('organization_id'))
            ->where('assigned_to', $user->id)->where('status', 'open')
            ->orderByRaw('due_date IS NULL')->orderBy('due_date')->orderBy('created_at')->limit(12)->get();
        $profile = $user->profile;

        return $this->read([
            'identity' => $this->workspace->person($user),
            'citizenship' => [
                'status' => $profile?->citizen ? 'citizen' : ($profile?->general_public ? 'public_member' : ($profile?->has_application ? 'submitted' : 'new')),
                'endorsements' => (int) ($profile?->endorse_cnt ?? 0), 'terms_accepted' => (bool) $profile?->signed_eula,
            ],
            'organizations' => $memberships->map(fn ($member) => $this->workspace->organization($member->organization, $member->role))->all(),
            // Pending invitees can see only the invitation, not its workspace.
            'invitations' => $invites->map(fn ($member) => [
                'organization_id' => $member->organization_id, 'name' => $member->organization->name,
                'description' => $member->organization->description, 'invited_at' => $member->updated_at->toISOString(),
                'invited_by' => $this->workspace->person(User::find($member->invited_by)),
            ])->all(),
            'tasks' => $tasks->map(fn ($task) => $this->workspace->task($task))->all(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $this->user($request);
        $request->validate(['page' => 'sometimes|integer|min:1']);
        $memberships = OrganizationMember::with('organization')->where('user_id', $user->id)->where('status', 'active')
            ->orderByDesc('updated_at')->simplePaginate(20);

        return $this->read([
            'data' => $memberships->getCollection()->map(fn ($m) => $this->workspace->organization($m->organization, $m->role))->all(),
            'next_page' => $memberships->hasMorePages() ? $memberships->currentPage() + 1 : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate(['client_id' => 'required|uuid', 'name' => 'required|string|max:100', 'description' => 'nullable|string|max:2000']);

        return $this->workspace->mutate($user, 'organization.create', $data, null, fn () => null, function () use ($user, $data) {
            abort_if(OrganizationMember::where('user_id', $user->id)->whereIn('status', ['active', 'invited'])->count() >= 100, 422, 'You have reached the limit of 100 organizations and invitations.');
            $organization = Organization::create([
                'id' => (string) Str::uuid(), 'name' => $data['name'], 'description' => $data['description'] ?? null, 'owner_id' => $user->id,
            ]);
            OrganizationMember::create([
                'organization_id' => $organization->id, 'user_id' => $user->id, 'role' => 'owner', 'status' => 'active',
                'invited_by' => $user->id, 'joined_at' => now(),
            ]);
            $this->workspace->record($organization->id, $user->id, 'organization_created', 'Created '.$organization->name.'.');

            return ['organization' => $this->workspace->organization($organization, 'owner')];
        }, 201);
    }

    public function show(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $membership = $this->workspace->member($organization, $user->id);
        $group = Organization::findOrFail($organization);
        $members = OrganizationMember::with(['user.citizen', 'user.civicWallet'])->where('organization_id', $organization)
            ->whereIn('status', $membership->role === 'owner' ? ['active', 'invited'] : ['active'])->orderBy('id')->get();

        return $this->read([
            'organization' => $this->workspace->organization($group, $membership->role),
            'members' => $members->map(fn ($m) => array_merge($this->workspace->person($m->user), [
                'role' => $m->role, 'status' => $m->status, 'joined_at' => $m->joined_at?->toISOString(),
            ]))->all(),
        ]);
    }

    public function invite(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate(['client_id' => 'required|uuid', 'address' => ['required', 'string', 'regex:/^[1-9A-HJ-NP-Za-km-z]{26,50}$/']]);

        return $this->workspace->mutate($user, $organization.'.invite', $data, $organization,
            function ($group) use ($organization, $user) {
                $this->workspace->member($organization, $user->id);
                $this->workspace->owner($group, $user->id);
            }, function ($group) use ($organization, $user, $data) {
                $wallet = CivicWallet::where('public_addr', $data['address'])->first();
                $target = $wallet ? User::find($wallet->user_id) : null;
                abort_unless($target && $target->status !== 'inactive', 422, 'No active civic identity was found at that address.');
                $targetId = (int) $wallet->user_id;
                $member = OrganizationMember::firstOrNew(['organization_id' => $organization, 'user_id' => $targetId]);
                abort_if($member->status === 'active', 409, 'This person is already a member.');
                if ($member->status !== 'invited') {
                    abort_if($group->members()->whereIn('status', ['active', 'invited'])->count() >= 100, 422, 'This organization has reached its 100-member limit.');
                    abort_if(OrganizationMember::where('user_id', $targetId)->whereIn('status', ['active', 'invited'])->count() >= 100, 422, 'This person cannot receive more organization invitations.');
                    $member->fill(['role' => 'member', 'status' => 'invited', 'invited_by' => $user->id, 'joined_at' => null])->save();
                    $this->workspace->record($organization, $user->id, 'member_invited', 'Invited '.$this->workspace->person($target)['name'].'.');
                }

                return ['invitation' => ['organization_id' => $organization, 'user_id' => $targetId, 'status' => 'invited']];
            }, 201);
    }

    public function respond(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate(['client_id' => 'required|uuid', 'accept' => 'required|boolean']);

        return $this->workspace->mutate($user, $organization.'.invitation.respond', $data, $organization,
            function () use ($organization, $user) {
                // A declined invitation can still replay its own receipt. A
                // removed/cancelled membership cannot recover old access.
                OrganizationMember::where('organization_id', $organization)->where('user_id', $user->id)
                    ->whereIn('status', ['invited', 'active', 'declined'])->firstOrFail();
            }, function () use ($organization, $user, $data) {
                $member = OrganizationMember::where('organization_id', $organization)->where('user_id', $user->id)->firstOrFail();
                abort_unless($member->status === 'invited', 409, 'This invitation has already been answered.');
                $member->status = $data['accept'] ? 'active' : 'declined';
                $member->joined_at = $data['accept'] ? now() : null;
                $member->save();
                $this->workspace->record($organization, $user->id, $data['accept'] ? 'member_joined' : 'member_declined',
                    $data['accept'] ? 'Joined the organization.' : 'Declined the invitation.');

                return ['membership' => ['organization_id' => $organization, 'status' => $member->status, 'role' => $member->role]];
            });
    }

    public function removeMember(Request $request, string $organization, int $member): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate(['client_id' => 'required|uuid']);

        return $this->workspace->mutate($user, $organization.'.member.'.$member.'.remove', $data, $organization,
            function ($group) use ($organization, $user, $member) {
                if ($member === (int) $user->id) {
                    // A departed member may replay only their departure receipt.
                    OrganizationMember::where('organization_id', $organization)->where('user_id', $user->id)->whereIn('status', ['active', 'removed'])->firstOrFail();
                } else {
                    $this->workspace->member($organization, $user->id);
                    $this->workspace->owner($group, $user->id);
                }
                abort_if((int) $group->owner_id === $member, 422, 'The owner must keep their membership. Archive the organization instead.');
            }, function () use ($organization, $user, $member) {
                $membership = OrganizationMember::where('organization_id', $organization)->where('user_id', $member)
                    ->whereIn('status', ['active', 'invited'])->firstOrFail();
                $membership->status = 'removed';
                $membership->save();
                OrganizationTask::where('organization_id', $organization)->where('assigned_to', $member)->where('status', 'open')
                    ->update(['assigned_to' => null, 'version' => DB::raw('version + 1'), 'updated_at' => now()]);
                $name = $this->workspace->person(User::find($member))['name'];
                $this->workspace->record($organization, $user->id, 'member_removed', $member === (int) $user->id ? 'Left the organization.' : 'Removed '.$name.'.');

                return ['membership' => ['user_id' => $member, 'status' => 'removed']];
            });
    }

    public function archive(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate(['client_id' => 'required|uuid']);

        return $this->workspace->mutate($user, $organization.'.archive', $data, $organization,
            function ($group) use ($organization, $user) {
                $this->workspace->member($organization, $user->id);
                $this->workspace->owner($group, $user->id);
            }, function ($group) use ($organization, $user) {
                $group->archived_at = now();
                $group->save();
                $this->workspace->record($organization, $user->id, 'organization_archived', 'Archived the organization. Its records remain available to members.');

                return ['organization' => $this->workspace->organization($group, 'owner')];
            });
    }

    public function tasks(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $this->workspace->member($organization, $user->id);
        $data = $request->validate(['status' => ['sometimes', Rule::in(['open', 'done', 'all'])], 'page' => 'sometimes|integer|min:1']);
        $query = OrganizationTask::where('organization_id', $organization);
        if (($data['status'] ?? 'open') !== 'all') {
            $query->where('status', $data['status'] ?? 'open');
        }
        $tasks = $query->orderByDesc('created_at')->orderBy('id')->simplePaginate(30);

        return $this->read([
            'data' => $tasks->getCollection()->map(fn ($task) => $this->workspace->task($task))->all(),
            'next_page' => $tasks->hasMorePages() ? $tasks->currentPage() + 1 : null,
        ]);
    }

    public function showTask(Request $request, string $organization, string $task): JsonResponse
    {
        $user = $this->user($request);
        $this->workspace->member($organization, $user->id);
        $item = OrganizationTask::where('organization_id', $organization)->whereKey($task)->firstOrFail();

        return $this->read(['task' => $this->workspace->task($item)]);
    }

    public function createTask(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate([
            'client_id' => 'required|uuid', 'title' => 'required|string|max:160', 'details' => 'nullable|string|max:5000',
            'assigned_to' => 'nullable|integer|min:1', 'due_date' => 'nullable|date_format:Y-m-d',
        ]);

        return $this->workspace->mutate($user, $organization.'.task.create', $data, $organization,
            fn () => $this->workspace->member($organization, $user->id), function () use ($organization, $user, $data) {
                $this->workspace->validateAssignee($organization, $data['assigned_to'] ?? null);
                $task = OrganizationTask::create([
                    'id' => (string) Str::uuid(), 'organization_id' => $organization, 'created_by' => $user->id,
                    'title' => $data['title'], 'details' => $data['details'] ?? null, 'assigned_to' => $data['assigned_to'] ?? null,
                    'due_date' => $data['due_date'] ?? null, 'status' => 'open', 'version' => 1,
                ]);
                $this->workspace->record($organization, $user->id, 'task_created', 'Created task: '.$task->title, $task->id, $this->workspace->task($task));

                return ['task' => $this->workspace->task($task)];
            }, 201);
    }

    public function updateTask(Request $request, string $organization, string $task): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate([
            'client_id' => 'required|uuid', 'version' => 'required|integer|min:1', 'title' => 'sometimes|required|string|max:160',
            'details' => 'sometimes|nullable|string|max:5000', 'assigned_to' => 'sometimes|nullable|integer|min:1',
            'due_date' => 'sometimes|nullable|date_format:Y-m-d', 'status' => ['sometimes', Rule::in(['open', 'done'])],
            'note' => 'nullable|string|max:5000', 'attachment_ids' => 'sometimes|array|max:4', 'attachment_ids.*' => 'required|uuid|distinct',
        ]);

        return $this->workspace->mutate($user, $organization.'.task.'.$task.'.update', $data, $organization,
            fn () => $this->workspace->member($organization, $user->id), function ($group) use ($organization, $user, $data, $task) {
                $item = OrganizationTask::where('organization_id', $organization)->whereKey($task)->firstOrFail();
                abort_unless(in_array((int) $user->id, [(int) $group->owner_id, (int) $item->created_by, (int) $item->assigned_to], true),
                    403, 'Only the owner, task creator, or assignee can update this task.');
                $this->workspace->checkVersion($item, (int) $data['version']);
                if (array_key_exists('assigned_to', $data)) {
                    $this->workspace->validateAssignee($organization, $data['assigned_to']);
                }
                $previousStatus = $item->status;
                foreach (['title', 'details', 'assigned_to', 'due_date', 'status'] as $field) {
                    if (array_key_exists($field, $data)) {
                        $item->$field = $data[$field];
                    }
                }
                $item->version = (int) $item->version + 1;
                if ($previousStatus !== $item->status) {
                    $item->completed_at = $item->status === 'done' ? now() : null;
                }
                $item->save();
                $kind = $previousStatus !== $item->status ? ($item->status === 'done' ? 'task_completed' : 'task_reopened') : 'task_updated';
                $label = ['task_completed' => 'Completed', 'task_reopened' => 'Reopened', 'task_updated' => 'Updated'][$kind];
                $body = $label.' task: '.$item->title;
                if (! empty($data['note'])) {
                    $body .= "\n\n".$data['note'];
                }
                $record = $this->workspace->record($organization, $user->id, $kind, $body, $task, $this->workspace->task($item), $data['attachment_ids'] ?? []);

                return ['task' => $this->workspace->task($item), 'activity' => $this->workspace->activity($record)];
            });
    }

    public function activity(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $this->workspace->member($organization, $user->id);
        $data = $request->validate(['before' => 'sometimes|integer|min:1']);
        $query = OrganizationActivity::with(['actor.citizen', 'actor.civicWallet', 'attachments'])->where('organization_id', $organization);
        if (isset($data['before'])) {
            $query->where('id', '<', $data['before']);
        }
        $records = $query->orderByDesc('id')->limit(31)->get();
        $visible = $records->take(30);

        return $this->read([
            'data' => $visible->map(fn ($record) => $this->workspace->activity($record))->values()->all(),
            'next_cursor' => $records->count() > 30 ? $visible->last()->id : null,
        ]);
    }

    public function createRecord(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $data = $request->validate([
            'client_id' => 'required|uuid', 'body' => 'required|string|max:5000', 'task_id' => 'nullable|uuid',
            'attachment_ids' => 'sometimes|array|max:4', 'attachment_ids.*' => 'required|uuid|distinct',
        ]);

        return $this->workspace->mutate($user, $organization.'.record.create', $data, $organization,
            fn () => $this->workspace->member($organization, $user->id), function () use ($organization, $user, $data) {
                if (! empty($data['task_id'])) {
                    OrganizationTask::where('organization_id', $organization)->whereKey($data['task_id'])->firstOrFail();
                }
                $record = $this->workspace->record($organization, $user->id, 'record', $data['body'], $data['task_id'] ?? null, [], $data['attachment_ids'] ?? []);

                return ['activity' => $this->workspace->activity($record)];
            }, 201);
    }

    public function upload(Request $request, string $organization): JsonResponse
    {
        $user = $this->user($request);
        $this->workspace->member($organization, $user->id);
        $data = $request->validate(['client_id' => 'required|uuid', 'photo' => 'required|file|image|mimes:jpg,jpeg,png,webp|max:5120']);
        $file = $request->file('photo');
        $dimensions = @getimagesize($file->getRealPath());
        abort_unless($dimensions && $dimensions[0] * $dimensions[1] <= 24000000, 422, 'Use a photo smaller than 24 megapixels.');
        $mime = $file->getMimeType();
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        abort_unless(isset($extensions[$mime]), 422, 'Use a JPEG, PNG, or WebP photo.');
        $data = ['client_id' => $data['client_id'], 'sha256' => hash_file('sha256', $file->getRealPath())];
        $storedPath = null;
        try {
            return $this->workspace->mutate($user, $organization.'.photo.upload', $data, $organization,
                fn () => $this->workspace->member($organization, $user->id), function () use ($organization, $user, $file, $mime, $extensions, $data, &$storedPath) {
                    $bytes = OrganizationAttachment::where('organization_id', $organization)->sum('size');
                    $personalBytes = OrganizationAttachment::where('uploaded_by', $user->id)->sum('size');
                    abort_if($personalBytes + $file->getSize() > 104857600, 422, 'You have reached your 100 MB photo limit.');
                    $free = disk_free_space(storage_path());
                    abort_if($free !== false && $free < 536870912, 503, 'Photo storage is temporarily full. Your draft can be retried later.');
                    abort_if($bytes + $file->getSize() > 524288000, 422, 'This organization has reached its 500 MB photo limit.');
                    $id = (string) Str::uuid();
                    $storedPath = $file->storeAs($organization, $id.'.'.$extensions[$mime], 'organization-media');
                    $attachment = OrganizationAttachment::create([
                        'id' => $id, 'organization_id' => $organization, 'uploaded_by' => $user->id, 'path' => $storedPath,
                        'mime' => $mime, 'size' => $file->getSize(), 'sha256' => $data['sha256'], 'created_at' => now(),
                    ]);

                    return ['attachment' => $this->workspace->attachment($attachment)];
                }, 201);
        } catch (\Throwable $error) {
            if ($storedPath && ! OrganizationAttachment::where('path', $storedPath)->exists()) {
                Storage::disk('organization-media')->delete($storedPath);
            }
            throw $error;
        }
    }

    public function photo(Request $request, string $organization, string $attachment): BinaryFileResponse
    {
        $user = $this->user($request);
        $this->workspace->member($organization, $user->id);
        $file = OrganizationAttachment::where('organization_id', $organization)->whereKey($attachment)->firstOrFail();
        // An uploaded draft is visible only to its author until it is shared.
        abort_unless($file->activity_id !== null || (int) $file->uploaded_by === (int) $user->id, 404);
        $path = Storage::disk('organization-media')->path($file->path);
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => $file->mime, 'Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; sandbox",
        ])->setPrivate();
    }
}
