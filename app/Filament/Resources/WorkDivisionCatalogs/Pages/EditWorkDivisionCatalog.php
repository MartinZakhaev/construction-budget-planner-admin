<?php

namespace App\Filament\Resources\WorkDivisionCatalogs\Pages;

use App\Filament\Resources\WorkDivisionCatalogs\WorkDivisionCatalogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkDivisionCatalog extends EditRecord
{
    protected static string $resource = WorkDivisionCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
