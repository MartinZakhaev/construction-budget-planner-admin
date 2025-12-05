<?php

namespace App\Filament\Resources\ItemCatalogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ItemCatalogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
                TextInput::make('default_price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
