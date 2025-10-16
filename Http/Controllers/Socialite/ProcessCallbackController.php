<?php

declare(strict_types=1);

/**
 * @see DutchCodingCompany\FilamentSocialite.
 */

namespace Modules\User\Http\Controllers\Socialite;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Modules\User\Actions\Socialite\IsRegistrationEnabledAction;
use Modules\User\Actions\Socialite\IsUserAllowedAction;
use Modules\User\Actions\Socialite\LoginUserAction;
use Modules\User\Actions\Socialite\RedirectToLoginAction;
use Modules\User\Actions\Socialite\RegisterOauthUserAction;
use Modules\User\Actions\Socialite\RegisterSocialiteUserAction;
use Modules\User\Actions\Socialite\RetrieveOauthUserAction;
use Modules\User\Actions\Socialite\RetrieveSocialiteUserAction;
use Modules\User\Actions\Socialite\SetDefaultRolesBySocialiteUserAction;
use Modules\User\Actions\Socialite\ValidateProviderAction;
use Modules\User\Events\RegistrationNotEnabled;
use Modules\User\Events\UserNotAllowed;
use Modules\Xot\Datas\XotData;

class ProcessCallbackController extends Controller
{
    public function __invoke(Request $request, string $provider): RedirectResponse
    {
        app(ValidateProviderAction::class)->execute($provider);

        // Recupero utente dal provider
        $oauthUser = app(RetrieveOauthUserAction::class)->execute($provider);
        if (null === $oauthUser) {
            return app(RedirectToLoginAction::class)->execute('auth.login-failed');
        }

        // Verifica se l'utente è autorizzato
        if (! app(IsUserAllowedAction::class)->execute($oauthUser)) {
            UserNotAllowed::dispatch($oauthUser);

            return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
        }

        // Cerco se esiste già un SocialiteUser
        $socialiteUser = app(RetrieveSocialiteUserAction::class)->execute($provider, $oauthUser);

        if ($socialiteUser) {
            if (! $socialiteUser->user?->canAccessSocialite()) {
                return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
            }

            // Aggiorno i token senza perdere il refresh_token
            $socialiteUser->update([
                'token' => $oauthUser->token,
                'expires_in' => $oauthUser->expiresIn,
                'token_expires_at' => now()->addSeconds($oauthUser->expiresIn),
                'refresh_token' => $oauthUser->refreshToken ?: $socialiteUser->refresh_token,
            ]);

            // Associo ruoli di default se necessario
            app(SetDefaultRolesBySocialiteUserAction::class, [
                'provider' => $provider,
            ])->execute($socialiteUser->user, $oauthUser);

            return app(LoginUserAction::class)->execute($socialiteUser);
        }

        // Se non esiste, verifico se la registrazione è permessa
        if (! app(IsRegistrationEnabledAction::class)->execute()) {
            RegistrationNotEnabled::dispatch($provider, $oauthUser);

            return app(RedirectToLoginAction::class)->execute('auth.registration-not-enabled');
        }

        $user_class = XotData::make()->getUserClass();
        /** @var \Modules\Xot\Contracts\UserContract */
        $user = $user_class::query()->firstWhere(['email' => $oauthUser->getEmail()]);

        // Creo il SocialiteUser
        if ($user) {
            $socialiteUser = app(RegisterSocialiteUserAction::class)->execute($provider, $oauthUser, $user);
        } else {
            $socialiteUser = app(RegisterOauthUserAction::class)->execute($provider, $oauthUser);
        }

        if (! $socialiteUser->user?->canAccessSocialite()) {
            return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
        }

        // Salvo i token al primo login
        $socialiteUser->update([
            'token' => $oauthUser->token,
            'refresh_token' => $oauthUser->refreshToken,
            'expires_in' => $oauthUser->expiresIn,
            'token_expires_at' => now()->addSeconds($oauthUser->expiresIn),
        ]);

        return app(LoginUserAction::class)->execute($socialiteUser);
    }
}
