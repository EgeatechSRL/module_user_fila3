<?php

declare(strict_types=1);

namespace Modules\User\Http\Middleware;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PasswordExpiryMiddleware
{
    public function handle(Request $request, \Closure $next): Response|RedirectResponse
    {
        $profile = $request->user()->profile;

        if ($request->routeIs('password.change') || $request->routeIs('password.update')) {
            return $next($request);
        }

        if ($request->routeIs('errors.password-expired') || $request->routeIs('*.auth.*')) {
            return $next($request);
        }

        if ($this->passwordHasExpired()) {
            if (Schema::hasColumn('profiles', 'logged_with_oauth') && $profile->logged_with_oauth) {
                return $next($request);
            } else {
                return redirect(route('errors.password-expired'));
            }
        }

        return $next($request);
    }

    protected function passwordHasExpired(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        if ($user->is_otp) {
            return true;
        }
        if (blank($user->password)) {
            return false;
        }

        if (blank($user->password_expires_at)) {
            return false;
        }

        if (now()->isAfter($user->password_expires_at)) {
            return true;
        }

        return false;
    }
}
