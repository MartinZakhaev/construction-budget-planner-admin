<?php

namespace App\Filament\Resources\ItemCatalogs\Pages;

use App\Filament\Resources\ItemCatalogs\ItemCatalogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListItemCatalogs extends ListRecords
{
    protected static string $resource = ItemCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
