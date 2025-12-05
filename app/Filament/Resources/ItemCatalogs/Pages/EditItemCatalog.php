<?php

namespace App\Filament\Resources\ItemCatalogs\Pages;

use App\Filament\Resources\ItemCatalogs\ItemCatalogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditItemCatalog extends EditRecord
{
    protected static string $resource = ItemCatalogResource::class;

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
