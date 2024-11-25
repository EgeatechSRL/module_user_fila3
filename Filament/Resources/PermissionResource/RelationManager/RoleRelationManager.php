<?php

/**
 * @see https://github.com/Althinect/filament-spatie-roles-permissions/blob/2.x/src/Resources/PermissionResource/RelationManager/RoleRelationManager.php
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources\PermissionResource\RelationManager;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;

class RoleRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    TextInput::make('name')
                        ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name')),
                    TextInput::make('guard_name')
                        ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name')),
                ]
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    TextColumn::make('name')
                        ->searchable()
                        ->label(__('filament-spatie-roles-permissions::filament-spatie.field.name')),
                    TextColumn::make('guard_name')
                        ->searchable()
                        ->label(__('filament-spatie-roles-permissions::filament-spatie.field.guard_name')),
                ]
            )
            ->filters(
                [
                ]
            );
    }

    protected static function getModelLabel(): ?string
    {
        // return __('filament-spatie-roles-permissions::filament-spatie.section.role');
        return __('filament-spatie-roles-permissions::filament-spatie.section.role');
    }

    protected static function getPluralModelLabel(): string
    {
        return __('filament-spatie-roles-permissions::filament-spatie.section.roles');
    }
}
