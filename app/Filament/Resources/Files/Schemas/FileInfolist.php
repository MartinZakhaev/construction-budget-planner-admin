<?php

namespace App\Filament\Resources\Files\Schemas;

use App\Models\File;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('owner_user_id'),
                TextEntry::make('project.name')
                    ->label('Project')
                    ->placeholder('-'),
                TextEntry::make('kind'),
                TextEntry::make('filename'),
                TextEntry::make('mime_type')
                    ->placeholder('-'),
                TextEntry::make('size_bytes')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('storage_path')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (File $record): bool => $record->trashed()),
            ]);
    }
}
