<?php

namespace App\Filament\Resources\WorkDivisionCatalogs\Pages;

use App\Filament\Resources\WorkDivisionCatalogs\WorkDivisionCatalogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkDivisionCatalog extends ViewRecord
{
    protected static string $resource = WorkDivisionCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
