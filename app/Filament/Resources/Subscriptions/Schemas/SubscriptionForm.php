<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('ACTIVE'),
                DateTimePicker::make('trial_ends_at'),
                DateTimePicker::make('current_period_start'),
                DateTimePicker::make('current_period_end'),
                DateTimePicker::make('canceled_at'),
            ]);
    }
}
