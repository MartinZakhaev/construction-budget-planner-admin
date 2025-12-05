<?php

namespace App\Filament\Resources\RabSummaries\Pages;

use App\Filament\Resources\RabSummaries\RabSummaryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRabSummaries extends ListRecords
{
    protected static string $resource = RabSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
