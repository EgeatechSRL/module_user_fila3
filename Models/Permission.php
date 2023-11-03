<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Modules\User\Models\Permission.
 *
 * @property string                                     $uuid
 * @property string                                     $name
 * @property string                                     $guard_name
 * @property Carbon|null                                $created_at
 * @property Carbon|null                                $updated_at
 * @property Collection<int, Permission>                $permissions
 * @property int|null                                   $permissions_count
 * @property Collection<int, \Modules\User\Models\Role> $roles
 * @property int|null                                   $roles_count
 * @property Collection<int, \Modules\User\Models\User> $users
 * @property int|null                                   $users_count
 *
 * @method static Builder|Permission                               newModelQuery()
 * @method static Builder|Permission                               newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static Builder|Permission                               query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static Builder|Permission                               whereCreatedAt($value)
 * @method static Builder|Permission                               whereGuardName($value)
 * @method static Builder|Permission                               whereName($value)
 * @method static Builder|Permission                               whereUpdatedAt($value)
 * @method static Builder|Permission                               whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Permission extends SpatiePermission
{
    /**
     * @var string
     */
    protected $connection = 'user';

    protected $fillable = ['id', 'name', 'guard_name', 'created_at', 'updated_at'];
}
