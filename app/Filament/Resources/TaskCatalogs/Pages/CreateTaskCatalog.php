<?php

namespace App\Filament\Resources\TaskCatalogs\Pages;

use App\Filament\Resources\TaskCatalogs\TaskCatalogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskCatalog extends CreateRecord
{
    protected static string $resource = TaskCatalogResource::class;
}
