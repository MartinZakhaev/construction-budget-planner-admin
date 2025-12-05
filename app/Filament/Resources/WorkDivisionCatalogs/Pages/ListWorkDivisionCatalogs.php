<?php

namespace App\Filament\Resources\WorkDivisionCatalogs\Pages;

use App\Filament\Resources\WorkDivisionCatalogs\WorkDivisionCatalogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkDivisionCatalogs extends ListRecords
{
    protected static string $resource = WorkDivisionCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
