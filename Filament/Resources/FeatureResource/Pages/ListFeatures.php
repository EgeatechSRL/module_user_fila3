<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\FeatureResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\User\Filament\Resources\FeatureResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;

class ListFeatures extends XotBaseListRecords
{
    protected static string $resource = FeatureResource::class;

    public function getGridTableColumns(): array
    {
        return [
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('name'),
            TextColumn::make('scope'),
            TextColumn::make('value'),
        ];
    }

    public function getTableFilters(): array
    {
        return [
        ];
    }

    public function getTableActions(): array
    {
        return [
            ViewAction::make()
                ->label(''),
            EditAction::make()
                ->label(''),
            DeleteAction::make()
                ->label('')
                ->requiresConfirmation(),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            DeleteBulkAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            // ->columns($this->getTableColumns())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())

            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->defaultSort(
                column: 'created_at',
                direction: 'DESC',
            );
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    
}
