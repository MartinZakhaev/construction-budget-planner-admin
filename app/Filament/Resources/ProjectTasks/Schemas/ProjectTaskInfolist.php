<?php

namespace App\Filament\Resources\ProjectTasks\Schemas;

use App\Models\ProjectTask;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectTaskInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('project.name')
                    ->label('Project'),
                TextEntry::make('project_division_id'),
                TextEntry::make('taskCatalog.name')
                    ->label('Task catalog'),
                TextEntry::make('display_name'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('row_version')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ProjectTask $record): bool => $record->trashed()),
            ]);
    }
}
