<?php

declare(strict_types=1);

namespace Modules\User\Models;

// use Laravel\Passport\AccessToken as PassportAccessToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Passport\Token as PassportToken;

/**
 * Modules\User\Models\OauthAccessToken.
 *
 * @property string                                $uuid
 * @property string|null                           $user_id
 * @property string                                $client_id
 * @property string|null                           $name
 * @property array|null                            $scopes
 * @property bool                                  $revoked
 * @property Carbon|null                           $created_at
 * @property Carbon|null                           $updated_at
 * @property Carbon|null                           $expires_at
 * @property \Modules\User\Models\OauthClient|null $client
 * @property \Modules\User\Models\User|null        $user
 *
 * @method static Builder|OauthAccessToken newModelQuery()
 * @method static Builder|OauthAccessToken newQuery()
 * @method static Builder|OauthAccessToken query()
 * @method static Builder|OauthAccessToken whereClientId($value)
 * @method static Builder|OauthAccessToken whereCreatedAt($value)
 * @method static Builder|OauthAccessToken whereExpiresAt($value)
 * @method static Builder|OauthAccessToken whereName($value)
 * @method static Builder|OauthAccessToken whereRevoked($value)
 * @method static Builder|OauthAccessToken whereScopes($value)
 * @method static Builder|OauthAccessToken whereUpdatedAt($value)
 * @method static Builder|OauthAccessToken whereUserId($value)
 * @method static Builder|OauthAccessToken whereUuid($value)
 *
 * @property string $id
 *
 * @method static Builder|OauthAccessToken whereId($value)
 *
 * @mixin \Eloquent
 */
class OauthAccessToken extends PassportToken
{
    /**
     * @var string
     */
    protected $connection = 'user';

    // protected $fillable = ['id', 'user_id', 'client_id', 'name', 'scopes', 'revoked', 'expires_at'];
}
