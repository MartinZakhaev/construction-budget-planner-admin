<?php

namespace App\Filament\Resources\RabSummaries\Pages;

use App\Filament\Resources\RabSummaries\RabSummaryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRabSummary extends ViewRecord
{
    protected static string $resource = RabSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
