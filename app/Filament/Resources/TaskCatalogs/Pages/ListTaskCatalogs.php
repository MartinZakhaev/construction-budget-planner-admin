<?php

namespace App\Filament\Resources\TaskCatalogs\Pages;

use App\Filament\Resources\TaskCatalogs\TaskCatalogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaskCatalogs extends ListRecords
{
    protected static string $resource = TaskCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
