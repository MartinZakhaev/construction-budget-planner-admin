<?php

namespace App\Filament\Resources\ProjectDivisions\Pages;

use App\Filament\Resources\ProjectDivisions\ProjectDivisionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProjectDivision extends EditRecord
{
    protected static string $resource = ProjectDivisionResource::class;

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
