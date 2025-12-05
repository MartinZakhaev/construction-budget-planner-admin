<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->required(),
                TextInput::make('owner_user_id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('location'),
                TextInput::make('tax_rate_percent')
                    ->required()
                    ->numeric()
                    ->default(11),
                TextInput::make('currency')
                    ->required()
                    ->default('IDR'),
            ]);
    }
}
