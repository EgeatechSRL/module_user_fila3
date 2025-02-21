<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Traits\HasTeams;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\RelationX;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modules\User\Models\User.
 *
 * @property Collection<int, OauthClient>                      $clients
 * @property int|null                                          $clients_count
 * @property Team|null                                         $currentTeam
 * @property Collection<int, Device>                           $devices
 * @property int|null                                          $devices_count
 * @property string|null                                       $full_name
 * @property DatabaseNotificationCollection<int, Notification> $notifications
 * @property int|null                                          $notifications_count
 * @property Collection<int, Team>                             $ownedTeams
 * @property int|null                                          $owned_teams_count
 * @property Collection<int, Permission>                       $permissions
 * @property int|null                                          $permissions_count
 * @property \Modules\Xot\Contracts\ProfileContract|null       $profile
 * @property Collection<int, Role>                             $roles
 * @property int|null                                          $roles_count
 * @property Collection<int, Team>                             $teams
 * @property int|null                                          $teams_count
 * @property Collection<int, OauthAccessToken>                 $tokens
 * @property int|null                                          $tokens_count
 *
 * @method static \Modules\User\Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User   newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User   newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User   permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User   query()
 * @method static \Illuminate\Database\Eloquent\Builder|User   role($roles, $guard = null)
 *
 * @property string                          $id
 * @property string                          $name
 * @property string                          $first_name
 * @property string                          $last_name
 * @property string                          $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string                          $password
 * @property string|null                     $remember_token
 * @property int|null                        $current_team_id
 * @property string|null                     $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $deleted_at
 * @property string|null                     $lang
 * @property bool                            $is_active
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 *
 * @mixin Eloquent
 *
 * @property Collection<int, Tenant> $tenants
 * @property int|null                $tenants_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 *
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_by
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedBy($value)
 *
 * @property string $surname
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 *
 * @property string|null $facebook_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFacebookId($value)
 * @method        bool                                       canAccessSocialite()
 *
 * @property TenantUser                         $pivot
 * @property Membership                         $membership
 * @property Collection<int, AuthenticationLog> $authentications
 * @property int|null                           $authentications_count
 * @property AuthenticationLog|null             $latestAuthentication
 *
 * @mixin \Eloquent
 */
abstract class BaseUser extends Authenticatable implements HasName, HasTenants, UserContract
{
    /* , HasAvatar, UserJetContract, ExportsPersonalData */
    /* , HasTeamsContract */
    use HasApiTokens;
    use HasFactory;

    // use TwoFactorAuthenticatable; //ArtMin96
    // use CanExportPersonalData; //ArtMin96
    use HasRoles;

    // use HasProfilePhoto; //ArtMin96
    // use HasTeams; //ArtMin96
    use HasTeams;
    use HasUuids;

    // use Traits\HasProfilePhoto;
    use Notifiable;
    use Traits\HasAuthenticationLogTrait;
    use Traits\HasTenants;
    use RelationX;

    public $incrementing = false;

    /** @var string */
    protected $connection = 'user';
    /** @var string */
    protected $primaryKey = 'id';
    /** @var string */
    protected $keyType = 'string';

    /** @var list<string> */
    protected $fillable = [
        'id',
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'lang',
        'current_team_id',
        'is_active',
        'is_otp', // is One Time Password
        'password_expires_at',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /** @var list<string> */
    protected $with = [
        'roles',
    ];

    /** @var list<string> */
    protected $appends = [
        // 'profile_photo_url',
    ];

    public function canAccessFilament(?Panel $panel = null): bool
    {
        // return $this->role_id === Role::ROLE_ADMINISTRATOR;
        return true;
    }

    public function getFilamentName(): string
    {
        return sprintf(
            '%s %s %s',
            $this->name,
            $this->first_name,
            $this->last_name,
        );
    }

    public function profile(): HasOne
    {
        /** @var class-string<Model> */
        $profileClass = XotData::make()->getProfileClass();

        return $this->hasOne($profileClass);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // $panel->default('admin');
        if ('admin' !== $panel->getId()) {
            $role = $panel->getId();
            /*
            $xot = XotData::make();
            if ($xot->super_admin === $this->email) {
                $role = Role::firstOrCreate(['name' => $role]);
                $this->assignRole($role);
            }
            */

            return $this->hasRole($role);
        }

        return true; // str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }

    public function canAccessSocialite(): bool
    {
        return true;
    }

    public function devices(): BelongsToMany
    {
        return $this
            ->belongsToManyX(Device::class);
    }

    public function socialiteUsers(): HasMany
    {
        return $this
            ->hasMany(SocialiteUser::class);
    }

    public function getProviderField(string $provider, string $field): string
    {
        $socialiteUser = $this->socialiteUsers()->firstWhere(['provider' => $provider]);
        if (null == $socialiteUser) {
            throw new \Exception('SocialiteUser not found');
        }

        $res = $socialiteUser->{$field};
        if (is_string($res)) {
            return $res;
        }
        throw new \Exception('SocialiteUser field [' . $field . '] not found');
    }

    // ----------------------
    // ----------------------
    // ---------------------
    /**
     * Get the entity's notifications.
     *
     * @return MorphMany<Notification, $this>
     */
    public function notifications()
    {
        // return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    public function getFullNameAttribute(?string $value): ?string
    {
        return $value ?? $this->first_name . ' ' . $this->last_name;
    }

    public function getNameAttribute(?string $value): ?string
    {
        if (null !== $value || null === $this->getKey()) {
            return $value;
        }
        $name = Str::of($this->email)->before('@')->toString();
        $i = 1;
        $value = $name . '-' . $i;
        while (null !== self::firstWhere(['name' => $value])) {
            ++$i;
            $value = $name . '-' . $i;
        }
        $this->update(['name' => $value]);

        return $value;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed', //Call to undefined cast [hashed] on column [password] in model [Modules\User\Models\User].
            'is_active' => 'boolean',
            'roles.pivot.id' => 'string',
            // https://github.com/beitsafe/laravel-uuid-auditing
            // ALTER TABLE model_has_role CHANGE COLUMN `id` `id` CHAR(37) NOT NULL DEFAULT uuid();

            'is_otp' => 'boolean',
            'password_expires_at' => 'datetime',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',

            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
}
