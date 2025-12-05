<?php

namespace App\Filament\Resources\ProjectDivisions\Pages;

use App\Filament\Resources\ProjectDivisions\ProjectDivisionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProjectDivisions extends ListRecords
{
    protected static string $resource = ProjectDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
