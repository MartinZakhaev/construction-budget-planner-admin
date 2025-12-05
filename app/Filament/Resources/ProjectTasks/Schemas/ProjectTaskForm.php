<?php

namespace App\Filament\Resources\ProjectTasks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                TextInput::make('project_division_id')
                    ->required(),
                Select::make('task_catalog_id')
                    ->relationship('taskCatalog', 'name')
                    ->required(),
                TextInput::make('display_name')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('row_version')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
