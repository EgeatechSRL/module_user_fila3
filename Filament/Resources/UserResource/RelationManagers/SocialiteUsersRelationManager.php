<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Modules\User\Models\SocialiteUser;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Filament\Traits\HasXotTable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;

/**
 * Class Modules\User\Filament\Resources\UserResource\RelationManagers\SocialiteUsersRelationManager.
 */
class SocialiteUsersRelationManager extends XotBaseRelationManager
{
    use HasXotTable;

    protected static string $relationship = 'socialiteUsers';

    /**
     * Configure the form schema for managing Socialite User data.
     */
    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema());
    }

    /**
     * Define form fields in a dedicated method for reusability.
     *
     * @return array<Forms\Components\Component>
     */
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('provider')
                ->label(__('Provider'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('Enter provider name, e.g., Google, Facebook')),

            Forms\Components\TextInput::make('provider_id')
                ->label(__('Provider ID'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('Enter the provider ID for the user')),

            Forms\Components\TextInput::make('name')
                ->label(__('Name'))
                ->maxLength(255)
                ->placeholder(__('User’s name associated with the provider')),

            Forms\Components\TextInput::make('email')
                ->label(__('Email'))
                ->email()
                ->maxLength(255)
                ->placeholder(__('User’s email associated with the provider')),

            Forms\Components\TextInput::make('avatar')
                ->label(__('Avatar URL'))
                ->url()
                ->maxLength(512)
                ->placeholder(__('URL of the user’s avatar image')),
        ];
    }

    /**
     * Define table columns in a separate, strongly-typed method.
     *
     * @return array<TextColumn|ImageColumn>
     */
    protected function getListTableColumns(): array
    {
        return [
            TextColumn::make('provider')
                ->label(__('Provider'))
                ->searchable(),

            TextColumn::make('provider_id')
                ->label(__('Provider ID'))
                ->searchable(),

            TextColumn::make('name')
                ->label(__('Name'))
                ->searchable(),

            TextColumn::make('email')
                ->label(__('Email'))
                ->searchable(),

            ImageColumn::make('avatar')
                ->label(__('Avatar'))
                ->size(40),
        ];
    }

    //  * Query scope to apply conditions to the relation manager.

    // protected function applyTableQueryScope(Builder $query): Builder
    // {
    //     return $query->when(
    //         in_array(SoftDeletingScope::class, class_uses_recursive(SocialiteUser::class)),
    //         fn (Builder $query) => $query->withTrashed()
    //     );
    // }
}
