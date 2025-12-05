<?php

namespace App\Filament\Resources\TaskLineItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TaskLineItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Select::make('project_task_id')
                    ->relationship('projectTask', 'id')
                    ->required(),
                Select::make('item_catalog_id')
                    ->relationship('itemCatalog', 'name')
                    ->required(),
                TextInput::make('description'),
                Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('line_total')
                    ->required()
                    ->numeric(),
                Toggle::make('taxable')
                    ->required(),
            ]);
    }
}
