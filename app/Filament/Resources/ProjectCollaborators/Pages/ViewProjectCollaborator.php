<?php

namespace App\Filament\Resources\ProjectCollaborators\Pages;

use App\Filament\Resources\ProjectCollaborators\ProjectCollaboratorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProjectCollaborator extends ViewRecord
{
    protected static string $resource = ProjectCollaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
