<?php

namespace App\Filament\Resources\Plans\Schemas;

use App\Models\Plan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('code'),
                TextEntry::make('name'),
                TextEntry::make('price_cents')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('interval'),
                TextEntry::make('max_projects')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Plan $record): bool => $record->trashed()),
            ]);
    }
}
