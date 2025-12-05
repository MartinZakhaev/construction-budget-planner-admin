<?php

namespace App\Filament\Resources\RabExports\Pages;

use App\Filament\Resources\RabExports\RabExportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRabExports extends ListRecords
{
    protected static string $resource = RabExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
