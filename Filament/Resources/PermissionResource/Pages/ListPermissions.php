<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\PermissionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Filament\Resources\PermissionResource;
use Modules\User\Models\User;
use Webmozart\Assert\Assert;

class ListPermissions extends ListRecords
{
    // //
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        Assert::classExists($roleModel = config('permission.models.role'));

        return [
            BulkAction::make('Attach Role')
                ->action(function (Collection $collection, array $data): void {
                    foreach ($collection as $record) {
                        Assert::isInstanceOf($record, User::class);
                        $record->roles()->sync($data['role']);
                        $record->save();
                    }
                })
                ->form([
                    Select::make('role')
                        ->label(__('filament-spatie-roles-permissions::filament-spatie.field.role'))
                        ->options($roleModel::query()->pluck('name', 'id'))
                        ->required(),
                ])->deselectRecordsAfterCompletion(),
        ];
    }
}