<?php

namespace App\Filament\Resources\Units\Schemas;

use App\Models\Unit;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Unit $record): bool => $record->trashed()),
            ]);
    }
}
