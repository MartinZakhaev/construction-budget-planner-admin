<?php

namespace App\Filament\Resources\TaskLineItems\Pages;

use App\Filament\Resources\TaskLineItems\TaskLineItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTaskLineItems extends ListRecords
{
    protected static string $resource = TaskLineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
