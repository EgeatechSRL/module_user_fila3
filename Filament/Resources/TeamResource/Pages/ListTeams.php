<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TeamResource\Pages;

use Filament\Actions\CreateAction;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\User\Filament\Resources\TeamResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;

class ListTeams extends XotBaseListRecords
{
    // //
    protected static string $resource = TeamResource::class;

    

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
