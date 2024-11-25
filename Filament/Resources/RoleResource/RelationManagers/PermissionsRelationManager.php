<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;

class PermissionsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'permissions';

    /**
     * Configura lo schema del form per la gestione dei permessi.
     */
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->label(__('Nome Permesso'))
                ->placeholder(__('Inserisci il nome del permesso')),
        ]);
    }

    /**
     * Configura la tabella per la visualizzazione e la gestione dei permessi.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nome'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([]) // Aggiungi eventuali filtri qui se necessario
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Crea Permesso'))
                    ->tooltip(__('Crea un nuovo permesso')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Modifica'))
                    ->tooltip(__('Modifica permesso')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('Elimina'))
                    ->tooltip(__('Elimina permesso')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('Elimina selezionati'))
                    ->tooltip(__('Elimina i permessi selezionati')),
            ]);
    }
}
