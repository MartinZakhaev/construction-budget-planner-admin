<?php

namespace App\Filament\Resources\OrganizationMembers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrganizationMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                TextInput::make('role')
                    ->required()
                    ->default('MEMBER'),
            ]);
    }
}
