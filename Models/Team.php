<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\User\Contracts\UserContract;

/**
 * Modules\User\Models\Team.
 *
 * @property int                                                                      $id
 * @property int                                                                      $user_id
 * @property string                                                                   $name
 * @property bool                                                                     $personal_team
 * @property \Illuminate\Support\Carbon|null                                          $created_at
 * @property \Illuminate\Support\Carbon|null                                          $updated_at
 * @property \Modules\User\Models\User|null                                           $owner
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\User> $users
 * @property int|null                                                                 $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePersonalTeam($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 *
 * @mixin IdeHelperTeam
 *
 * @property \Illuminate\Database\Eloquent\Collection<int, \Modules\User\Models\TeamInvitation> $teamInvitations
 * @property int|null                                                                           $team_invitations_count
 *
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
        return $this->belongsTo(FilamentJet::userModel(), 'user_id');
    }

    /**
     * Get all of the team's users including its owner.
     */
    public function allUsers(): Collection
    {
        if (! $this->owner instanceof \Modules\User\Models\User) {
            return $this->users;
        }

        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        $pivotClass = $xot->getMembershipClass();
        $pivot = app($pivotClass);
        $pivotTable = $pivot->getTable();
        $pivotDbName = $pivot->getConnection()->getDatabaseName();
        $pivotTableFull = $pivotDbName.'.'.$pivotTable;

        return $this->belongsToMany(FilamentJet::userModel(), $pivotTableFull, 'team_id')
            ->using($pivotClass)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(UserContract $userContract): bool
    {
        return $this->users->contains($userContract) || $userContract->ownsTeam($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(fn ($user): bool => $user->email === $email);
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
        return $this->hasMany(FilamentJet::teamInvitationModel());
    }

    /**
     * Remove the given user from the team.
     */
    public function removeUser(UserContract $userContract): void
    {
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
