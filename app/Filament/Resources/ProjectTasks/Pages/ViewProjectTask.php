<?php

namespace App\Filament\Resources\ProjectTasks\Pages;

use App\Filament\Resources\ProjectTasks\ProjectTaskResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectTask extends ViewRecord
{
    protected static string $resource = ProjectTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
