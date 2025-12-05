<?php

namespace App\Filament\Resources\RabSummaries;

use App\Filament\Resources\RabSummaries\Pages\CreateRabSummary;
use App\Filament\Resources\RabSummaries\Pages\EditRabSummary;
use App\Filament\Resources\RabSummaries\Pages\ListRabSummaries;
use App\Filament\Resources\RabSummaries\Pages\ViewRabSummary;
use App\Filament\Resources\RabSummaries\Schemas\RabSummaryForm;
use App\Filament\Resources\RabSummaries\Schemas\RabSummaryInfolist;
use App\Filament\Resources\RabSummaries\Tables\RabSummariesTable;
use App\Models\RabSummary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RabSummaryResource extends Resource
{
    protected static ?string $model = RabSummary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RabSummaryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RabSummaryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RabSummariesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRabSummaries::route('/'),
            'create' => CreateRabSummary::route('/create'),
            'view' => ViewRabSummary::route('/{record}'),
            'edit' => EditRabSummary::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
