<?php

namespace App\Filament\Resources\RabSummaries\Schemas;

use App\Models\RabSummary;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RabSummaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('version')
                    ->numeric(),
                TextEntry::make('subtotal_material')
                    ->numeric(),
                TextEntry::make('subtotal_manpower')
                    ->numeric(),
                TextEntry::make('subtotal_tools')
                    ->numeric(),
                TextEntry::make('taxable_subtotal')
                    ->numeric(),
                TextEntry::make('nontax_subtotal')
                    ->numeric(),
                TextEntry::make('tax_rate_percent')
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->numeric(),
                TextEntry::make('grand_total')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_by'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (RabSummary $record): bool => $record->trashed()),
            ]);
    }
}
