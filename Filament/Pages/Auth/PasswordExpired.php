<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Auth;

use EightyNine\FilamentPasswordExpiry\Events\NewPasswordSet;
use EightyNine\FilamentPasswordExpiry\Http\Response\PasswordResetResponse;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Modules\Xot\Filament\Traits\NavigationPageLabelTrait;
use Webmozart\Assert\Assert;

/**
 * @property ComponentContainer $form
 * @property ComponentContainer $editProfileForm
 * @property ComponentContainer $editPasswordForm
 */
class PasswordExpired extends Page implements HasForms
{
    use InteractsWithFormActions;

    use NavigationPageLabelTrait;

    /**
     * @var view-string
     */
    protected static string $view = 'user::filament.auth.pages.password-expired';

    public ?string $current_password = '';

    public ?string $password = '';

    public ?string $passwordConfirmation = '';
    protected static bool $shouldRegisterNavigation = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getCurrentPasswordFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('current_password')
            // ->label(__('password-expiry::password-expiry.reset-password.form.current_password.label'))
            ->label(static::trans('fields.current_password.label'))
            ->password()
            // ->revealable(filament()->arePasswordsRevealable())
            ->revealable()
            ->required()
            ->rule(PasswordRule::default())
            ->validationAttribute(static::trans('fields.current_password.validation_attribute'));
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(static::trans('fields.password.label'))
            ->password()
            // ->revealable(filament()->arePasswordsRevealable())
            ->revealable()
            ->required()
            ->rule(PasswordRule::default())
            ->same('passwordConfirmation')
            ->validationAttribute(static::trans('fields.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(static::trans('fields.password_confirmation.label'))
            ->password()
            // ->revealable(filament()->arePasswordsRevealable())
            ->revealable()
            ->required()
            ->dehydrated(false);
    }

    /**
     * @return array<Action|ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }

    public function getResetPasswordFormAction(): Action
    {
        return Action::make('resetPassword')
            ->label(static::trans('actions.reset_password.label'))
            ->submit('resetPassword');
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public function resetPassword(): ?PasswordResetResponse
    {
        // get new password
        $data = $this->form->getState();

        // get auth object
        $authObject = auth()->user();

        // check if current password is correct
        Assert::string($current_password = $this->form->getState()['current_password']);
        if (! Hash::check($current_password, $authObject->password)) {
            Notification::make()
                ->title(__('password-expiry::password-expiry.reset-password.notifications.wrong_password.title'))
                ->body(__('password-expiry::password-expiry.reset-password.notifications.wrong_password.body'))
                ->danger()
                ->send();

            return null;
        }

        // check if new password is different from the current password
        if (Hash::check($data['password'], $authObject->password)) {
            Notification::make()
                ->title(__('password-expiry::password-expiry.reset-password.notifications.same_password.title'))
                ->body(__('password-expiry::password-expiry.reset-password.notifications.same_password.body'))
                ->danger()
                ->send();

            return null;
        }

        // check if both required columns exist in the database
        if (! Schema::hasColumn('users', 'password_expires_at')) {
            Notification::make()
                ->title(__('password-expiry::password-expiry.reset-password.notifications.column_not_found.title'))
                ->body(__('password-expiry::password-expiry.reset-password.notifications.column_not_found.body', [
                    'column_name' => 'password_expires_at',
                    'password_column_name' => 'password',
                    'table_name' => 'users',
                ]))
                ->danger()
                ->send();

            return null;
        }

        // get password expiry date and time
        $passwordExpiryDateTime = now()->addDays(30);

        // set password expiry date and time
        $authObject->{'password_expires_at'} = $passwordExpiryDateTime;
        $authObject->password = $data['password'];
        $authObject->save();

        // load up user email
        $data['email'] = $authObject->email;

        event(new NewPasswordSet($authObject));

        Notification::make()
            ->title(__('password-expiry::password-expiry.reset-password.notifications.password_reset.success'))
            ->success()
            ->send();

        return new PasswordResetResponse();
    }
}