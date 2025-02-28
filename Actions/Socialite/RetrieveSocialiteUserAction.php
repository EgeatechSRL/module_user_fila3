<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Models\SocialiteUser;
use Spatie\QueueableAction\QueueableAction;

class RetrieveSocialiteUserAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(string $provider, SocialiteUserContract $user): ?SocialiteUser
    {
        $databaseUser = SocialiteUser::query()
            ->with(['user'])
            ->where('provider', $provider)
            ->where('provider_id', $user->getId())
            ->first();

        if ($databaseUser !== null) {
            $databaseUser->token = $user->token;
            $databaseUser->avatar = $user->avatar;
            if (!is_null($user->refreshToken)) {
                $databaseUser->refresh_token = $user->refreshToken;
            }
            $databaseUser->save();
            return $databaseUser->refresh();
        }

        return null;
    }
}
