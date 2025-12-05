<?php

namespace App\Filament\Resources\ItemCatalogs\Pages;

use App\Filament\Resources\ItemCatalogs\ItemCatalogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewItemCatalog extends ViewRecord
{
    protected static string $resource = ItemCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
