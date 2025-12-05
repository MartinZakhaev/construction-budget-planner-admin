<?php

namespace App\Filament\Resources\RabSummaries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RabSummaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                TextInput::make('version')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('subtotal_material')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subtotal_manpower')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subtotal_tools')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('taxable_subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('nontax_subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax_rate_percent')
                    ->required()
                    ->numeric()
                    ->default(11),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->required(),
            ]);
    }
}
