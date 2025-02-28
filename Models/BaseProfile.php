<?php

declare(strict_types=1);

namespace Modules\User\Models;

// use Illuminate\Database\Eloquent\Relations\HasOne;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\Traits\IsProfileTrait;
use Modules\Xot\Contracts\ProfileContract;
use Parental\HasChildren;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

/**
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes                                                             $extra
 * @property string                                                                                                        $avatar
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>                                                     $deviceUsers
 * @property int|null                                                                                                      $device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>                                                         $devices
 * @property int|null                                                                                                      $devices_count
 * @property string|null                                                                                                   $first_name
 * @property string|null                                                                                                   $full_name
 * @property string|null                                                                                                   $last_name
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media>    $media
 * @property int|null                                                                                                      $media_count
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>                                                     $mobileDeviceUsers
 * @property int|null                                                                                                      $mobile_device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>                                                         $mobileDevices
 * @property int|null                                                                                                      $mobile_devices_count
 * @property \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property int|null                                                                                                      $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Permission>                                                     $permissions
 * @property int|null                                                                                                      $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Role>                                                           $roles
 * @property int|null                                                                                                      $roles_count
 * @property \Modules\Xot\Contracts\UserContract|null                                                                      $user
 * @property string|null                                                                                                   $user_name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseProfile     withExtraAttributes()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileContract withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
abstract class BaseProfile extends BaseModel implements ProfileContract
{
    use HasChildren;
    use HasRoles;
    use InteractsWithMedia;
    use IsProfileTrait;
    use Notifiable;
    use SchemalessAttributesTrait;

    /**
     * Undocumented variable.
     * Property Modules\Xot\Models\Profile::$guard_name is never read, only written.
     */
    // private string $guard_name = 'web';

    /** @var list<string> */
    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'type',
        'first_name',
        'last_name',
        'phone',
        'email',
        'bio',
        'is_active',
        'extra',
    ];

    /** @var list<string> */
    protected $appends = [
        'full_name',
    ];

    /** @var list<string> */
    protected $with = [
        'user',
    ];

    /** @var array */
    protected $schemalessAttributes = [
        'extra',
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra->modelScope();
    }

    public function getAvatarUrl(): string
    {
        $user = Auth::user();
        $avatar = '';

        $socialiteUser = SocialiteUser::query()
            ->with(['user'])
            ->where('provider', 'google')
            ->where('user_id', $user->id)
            ->first();

        if (! is_null($socialiteUser)) {
            $avatar = $socialiteUser->avatar;
        } else {
            $name = str(Filament::getNameForDefaultAvatar($user))
                ->trim()
                ->explode(' ')
                ->map(fn(string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
                ->join(' ');

            $avatar = 'https://source.boringavatars.com/beam/120/' . urlencode($name);
        }

        return $avatar;
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',

            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',

            'is_active' => 'boolean',
            'extra' => SchemalessAttributes::class,
        ];
    }
}
