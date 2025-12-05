<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use App\Models\Subscription;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubscriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('user.id')
                    ->label('User'),
                TextEntry::make('plan.name')
                    ->label('Plan'),
                TextEntry::make('status'),
                TextEntry::make('trial_ends_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('current_period_start')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('current_period_end')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('canceled_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Subscription $record): bool => $record->trashed()),
            ]);
    }
}
