<?php

namespace App\Filament\Resources\OrganizationMembers\Schemas;

use App\Models\OrganizationMember;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrganizationMemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('organization.name')
                    ->label('Organization'),
                TextEntry::make('user.id')
                    ->label('User'),
                TextEntry::make('role'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (OrganizationMember $record): bool => $record->trashed()),
            ]);
    }
}
