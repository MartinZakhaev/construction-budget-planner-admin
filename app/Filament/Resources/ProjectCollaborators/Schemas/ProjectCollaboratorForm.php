<?php

namespace App\Filament\Resources\ProjectCollaborators\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectCollaboratorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                TextInput::make('role')
                    ->required()
                    ->default('EDITOR'),
            ]);
    }
}
