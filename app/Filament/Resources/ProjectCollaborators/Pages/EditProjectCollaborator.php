<?php

namespace App\Filament\Resources\ProjectCollaborators\Pages;

use App\Filament\Resources\ProjectCollaborators\ProjectCollaboratorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectCollaborator extends EditRecord
{
    protected static string $resource = ProjectCollaboratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
