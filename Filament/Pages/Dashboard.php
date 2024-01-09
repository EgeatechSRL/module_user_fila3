<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\User\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'user::filament.pages.dashboard';

    /*
    public function mount(): void {
        $user = auth()->user();
        if(!$user->hasRole('super-admin')){
            redirect('/admin');
        }
    }
    */
}