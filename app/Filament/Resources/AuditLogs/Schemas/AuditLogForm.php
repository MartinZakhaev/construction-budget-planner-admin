<?php

namespace App\Filament\Resources\AuditLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id'),
                Select::make('project_id')
                    ->relationship('project', 'name'),
                TextInput::make('action')
                    ->required(),
                TextInput::make('entity_table'),
                TextInput::make('entity_id'),
                TextInput::make('meta'),
                TextInput::make('ip'),
                Textarea::make('user_agent')
                    ->columnSpanFull(),
            ]);
    }
}
