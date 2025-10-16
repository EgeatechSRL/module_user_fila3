<?php

declare(strict_types=1);

/**
 * @see DutchCodingCompany\FilamentSocialite.
 */

namespace Modules\User\Http\Controllers\Socialite;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\User\Models\SocialiteUser;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Actions\Socialite\ValidateProviderAction;
use Modules\User\Actions\Socialite\GetProviderScopesAction;

class RedirectToProviderController extends Controller
{
    public function __invoke(Request $request, string $provider, ?string $force_consent = null): RedirectResponse
    {
        app(ValidateProviderAction::class)->execute($provider);

        $scopes = app(GetProviderScopesAction::class)->execute($provider);
        $socialiteProvider = Socialite::with($provider);

        if (! is_object($socialiteProvider)) {
            throw new \Exception('Provider not supported by Socialite');
        }

        if (! method_exists($socialiteProvider, 'scopes')) {
            throw new \Exception('Scopes not supported on this provider');
        }

        Session::put('auth.relayUrl', URL::previous());

        $extraParams = ['access_type' => 'offline'];

        if ($force_consent) {
            $extraParams['prompt'] = 'consent';
        }

        return $socialiteProvider
            ->scopes($scopes)
            ->with($extraParams)
            ->redirect();
    }
}
