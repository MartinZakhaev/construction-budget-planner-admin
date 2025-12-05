<?php

namespace App\Filament\Resources\TaskLineItems\Schemas;

use App\Models\TaskLineItem;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskLineItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('projectTask.id')
                    ->label('Project task'),
                TextEntry::make('itemCatalog.name')
                    ->label('Item catalog'),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('unit.name')
                    ->label('Unit'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('unit_price')
                    ->money(),
                TextEntry::make('line_total')
                    ->numeric(),
                IconEntry::make('taxable')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TaskLineItem $record): bool => $record->trashed()),
            ]);
    }
}
