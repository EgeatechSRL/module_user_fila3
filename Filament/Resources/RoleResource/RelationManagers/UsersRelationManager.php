<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\TransTrait;

/**
 * UsersRelationManager.
 *
 * Manages the relationship between users and roles, providing functionality
 * for viewing, filtering, and managing users associated with a specific role.
 */
final class UsersRelationManager extends XotBaseRelationManager
{
    use TransTrait;
    use HasXotTable;

    protected static string $relationship = 'users';
    protected static ?string $inverseRelationship = 'roles';
    protected static ?string $recordTitleAttribute = 'name';
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    /**
     * Defines the form schema for creating or editing a user within this relation.
     */
    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema());
    }

    /**
     * Returns the form schema structure, defining the input fields for user data.
     *
     * @return array the form schema configuration array
     */
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label(__('user::socialite_user.fields.name.label')),
            // Additional fields can be added here as necessary
        ];
    }

    /**
     * Defines the columns displayed in the users list table.
     *
     * @return array the columns configuration array
     */
    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('user::fields.name'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('email')
                ->label(__('user::fields.email'))
                ->searchable()
                ->sortable()
                ->copyable(),

            TextColumn::make('created_at')
                ->label(__('user::fields.created_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(),

            TextColumn::make('updated_at')
                ->label(__('user::fields.updated_at'))
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * Configures available filters for the table, enabling users to refine their view.
     *
     * @return array the filters configuration array
     */
    protected function getTableFilters(): array
    {
        return [
            Filter::make('active')
                ->label(__('user::filters.active_users'))
                ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                ->toggle(),

            Filter::make('created_at')
                ->label(__('user::filters.creation_date'))
                ->form([
                    Forms\Components\DatePicker::make('created_from')->label(__('user::filters.created_from')),
                    Forms\Components\DatePicker::make('created_until')->label(__('user::filters.created_until')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['created_from'], fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'], fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                })
                ->columns(2),
        ];
    }

    /**
     * Defines header actions for the table, typically used for adding or associating records.
     *
     * @return array the header actions configuration array
     */
    protected function getHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label('')
                ->tooltip(__('Create User')),

            Tables\Actions\AssociateAction::make()
                ->label('')
                ->tooltip(__('Associate User')),
        ];
    }

    /**
     * Configures individual record actions, enabling view, edit, and detach functionality.
     *
     * @return array the actions configuration array
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label('')
                ->tooltip(__('role.view_user'))
                ->icon('heroicon-o-eye'),

            Tables\Actions\EditAction::make()
                ->label('')
                ->tooltip(__('role.edit_user'))
                ->icon('heroicon-o-pencil'),

            Tables\Actions\DetachAction::make()
                ->label('')
                ->tooltip(__('role.detach_user'))
                ->icon('heroicon-o-link-slash'),
        ];
    }

    /**
     * Defines bulk actions that can be performed on multiple records simultaneously.
     *
     * @return array the bulk actions configuration array
     */
    protected function getBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make()
                ->label('')
                ->tooltip(__('Delete Selected')),
        ];
    }
}
