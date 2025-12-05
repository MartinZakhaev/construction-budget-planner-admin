<?php

namespace App\Filament\Resources\ProjectTasks\Pages;

use App\Filament\Resources\ProjectTasks\ProjectTaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectTasks extends ListRecords
{
    protected static string $resource = ProjectTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
