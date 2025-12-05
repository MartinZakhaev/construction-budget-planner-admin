<?php

namespace App\Filament\Resources\Files\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('owner_user_id')
                    ->required(),
                Select::make('project_id')
                    ->relationship('project', 'name'),
                TextInput::make('kind')
                    ->required()
                    ->default('OTHER'),
                TextInput::make('filename')
                    ->required(),
                TextInput::make('mime_type'),
                TextInput::make('size_bytes')
                    ->numeric(),
                Textarea::make('storage_path')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
