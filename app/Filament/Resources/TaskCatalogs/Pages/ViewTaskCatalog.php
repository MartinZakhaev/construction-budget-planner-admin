<?php

namespace App\Filament\Resources\TaskCatalogs\Pages;

use App\Filament\Resources\TaskCatalogs\TaskCatalogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTaskCatalog extends ViewRecord
{
    protected static string $resource = TaskCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
