<?php

namespace App\Filament\Resources\TaskCatalogs\Pages;

use App\Filament\Resources\TaskCatalogs\TaskCatalogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTaskCatalog extends EditRecord
{
    protected static string $resource = TaskCatalogResource::class;

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
