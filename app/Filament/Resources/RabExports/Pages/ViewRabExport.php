<?php

namespace App\Filament\Resources\RabExports\Pages;

use App\Filament\Resources\RabExports\RabExportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRabExport extends ViewRecord
{
    protected static string $resource = RabExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
