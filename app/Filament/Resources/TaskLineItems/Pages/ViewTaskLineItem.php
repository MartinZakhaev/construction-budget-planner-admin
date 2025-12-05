<?php

namespace App\Filament\Resources\TaskLineItems\Pages;

use App\Filament\Resources\TaskLineItems\TaskLineItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTaskLineItem extends ViewRecord
{
    protected static string $resource = TaskLineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
