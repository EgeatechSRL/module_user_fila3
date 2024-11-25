<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Modules\Xot\Filament\Traits\HasXotTable;
use Filament\Resources\RelationManagers\RelationManager;
use Modules\User\Filament\Resources\TenantResource\Pages\ListTenants;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;

/**
 * Manages the relationship between users and tenants.
 *
 * This class provides the form schema and table configuration for the "tenants" relationship
 * with strong typing and enhanced structure for stability and professionalism.
 */
class TenantsRelationManager extends XotBaseRelationManager
{
    use HasXotTable;
    protected static string $relationship = 'tenants';
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Set up the form schema for tenant relations.
     *
     * @param Form $form the form instance for configuration
     *
     * @return Form configured form instance
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('user::fields.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * Define table columns for displaying tenant information.
     *
     * @return array<int, \Filament\Tables\Columns\Column> configured table columns
     */
    public function getListTableColumns(): array
    {
        return app(ListTenants::class)->getListTableColumns();
    }
}
