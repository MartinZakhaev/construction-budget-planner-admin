<?php

namespace App\Filament\Resources\WorkDivisionCatalogs\Schemas;

use App\Models\WorkDivisionCatalog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WorkDivisionCatalogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
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
                    ->visible(fn (WorkDivisionCatalog $record): bool => $record->trashed()),
            ]);
    }
}
