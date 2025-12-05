<?php

namespace App\Filament\Resources\RabExports\Schemas;

use App\Models\RabExport;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RabExportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('rab_summary_id'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('pdfFile.id')
                    ->label('Pdf file')
                    ->placeholder('-'),
                TextEntry::make('xlsxFile.id')
                    ->label('Xlsx file')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (RabExport $record): bool => $record->trashed()),
            ]);
    }
}
