<?php

namespace App\Filament\Resources\ItemCatalogs\Schemas;

use App\Models\ItemCatalog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemCatalogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('type'),
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('unit.name')
                    ->label('Unit'),
                TextEntry::make('default_price')
                    ->money(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ItemCatalog $record): bool => $record->trashed()),
            ]);
    }
}
