<?php

namespace App\Filament\Resources\TaskCatalogs\Schemas;

use App\Models\TaskCatalog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TaskCatalogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('division.name')
                    ->label('Division'),
                TextEntry::make('code'),
                TextEntry::make('name'),
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
                    ->visible(fn (TaskCatalog $record): bool => $record->trashed()),
            ]);
    }
}
