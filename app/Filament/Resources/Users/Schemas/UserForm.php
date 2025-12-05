<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Textarea::make('password')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('full_name'),
                TextInput::make('profile_file_id'),
            ]);
    }
}
