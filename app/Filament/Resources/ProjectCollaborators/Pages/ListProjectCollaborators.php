<?php

namespace App\Filament\Resources\ProjectCollaborators\Pages;

use App\Filament\Resources\ProjectCollaborators\ProjectCollaboratorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectCollaborators extends ListRecords
{
    protected static string $resource = ProjectCollaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
