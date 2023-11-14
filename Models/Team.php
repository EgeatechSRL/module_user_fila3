<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;

/**
 * Modules\User\Models\Team.
 *
 * @property int                                                                                $id
 * @property int                                                                                $user_id
 * @property string                                                                             $name
 * @property int                                                                                $personal_team
 * @property Carbon|null                                                                        $created_at
 * @property Carbon|null                                                                        $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\User>           $members
 * @property int|null                                                                           $members_count
 * @property \Modules\User\Models\User|null                                                     $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\TeamInvitation> $teamInvitations
 * @property int|null                                                                           $team_invitations_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\User>           $users
 * @property int|null                                                                           $users_count
 * @method static \Modules\User\Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static Builder|Team                                 newModelQuery()
 * @method static Builder|Team                                 newQuery()
 * @method static Builder|Team                                 query()
 * @method static Builder|Team                                 whereCreatedAt($value)
 * @method static Builder|Team                                 whereId($value)
 * @method static Builder|Team                                 whereName($value)
 * @method static Builder|Team                                 wherePersonalTeam($value)
 * @method static Builder|Team                                 whereUpdatedAt($value)
 * @method static Builder|Team                                 whereUserId($value)
 * @mixin \Eloquent
 */
class Team extends BaseModel implements TeamContract
{
    protected $fillable = [
        'user_id',
        'name',
        'personal_team',
    ];

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        $xotData = XotData::make();

        return $this->belongsTo($xotData->getUserClass(), 'user_id');
    }

    /**
     * Get all of the team's users including its owner.
     */
    public function allUsers(): Collection
    {
        if (! $this->owner instanceof User) {
            return $this->users;
        }

        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        $xotData = XotData::make();
        $userClass = $xotData->getUserClass();
        $membershipClass = $xotData->getMembershipClass();
        $pivot = app($membershipClass);
        $pivotTable = $pivot->getTable();
        $pivotDbName = $pivot->getConnection()->getDatabaseName();
        $pivotTableFull = $pivotDbName.'.'.$pivotTable;

        return $this->belongsToMany($userClass, $pivotTableFull, 'team_id')
            ->using($membershipClass)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    public function members(): BelongsToMany
    {
        return $this->users();
    }

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(UserContract $user): bool
    {
        // Parameter #1 $key of method Illuminate\Database\Eloquent\Collection<int,Modules\User\Models\User>::contains() expects (callable(Modules\User\Models\User, int):
        // bool)|int|Modules\User\Models\User|string, Modules\User\Contracts\UserContract given.
        // ✏️  User\Models\Team.php
        if ($this->users->contains(get_class($user))) {
            return true;
        }

        return $user->ownsTeam($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(static fn ($user): bool => $user->email === $email);
    }

    /**
     * Determine if the given user has the given permission on the team.
     */
    public function userHasPermission(UserContract $userContract, string $permission): bool
    {
        return $userContract->hasTeamPermission($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the team.
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * Remove the given user from the team.
     */
    public function removeUser(UserContract $userContract): void
    {
        /* @phpstan-ignore-next-line */
        if ($userContract->current_team_id === $this->id) {
            $userContract->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($userContract);
    }

    /**
     * Purge all of the team's resources.
     */
    public function purge(): void
    {
        $this->owner()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)
            ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
