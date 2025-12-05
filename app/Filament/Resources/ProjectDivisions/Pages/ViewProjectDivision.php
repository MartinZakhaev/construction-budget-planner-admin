<?php

namespace App\Filament\Resources\ProjectDivisions\Pages;

use App\Filament\Resources\ProjectDivisions\ProjectDivisionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectDivision extends ViewRecord
{
    protected static string $resource = ProjectDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
