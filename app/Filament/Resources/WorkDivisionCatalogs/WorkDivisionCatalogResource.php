<?php

namespace App\Filament\Resources\WorkDivisionCatalogs;

use App\Filament\Resources\WorkDivisionCatalogs\Pages\CreateWorkDivisionCatalog;
use App\Filament\Resources\WorkDivisionCatalogs\Pages\EditWorkDivisionCatalog;
use App\Filament\Resources\WorkDivisionCatalogs\Pages\ListWorkDivisionCatalogs;
use App\Filament\Resources\WorkDivisionCatalogs\Pages\ViewWorkDivisionCatalog;
use App\Filament\Resources\WorkDivisionCatalogs\Schemas\WorkDivisionCatalogForm;
use App\Filament\Resources\WorkDivisionCatalogs\Schemas\WorkDivisionCatalogInfolist;
use App\Filament\Resources\WorkDivisionCatalogs\Tables\WorkDivisionCatalogsTable;
use App\Models\WorkDivisionCatalog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkDivisionCatalogResource extends Resource
{
    protected static ?string $model = WorkDivisionCatalog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WorkDivisionCatalogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkDivisionCatalogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkDivisionCatalogsTable::configure($table);
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
            'index' => ListWorkDivisionCatalogs::route('/'),
            'create' => CreateWorkDivisionCatalog::route('/create'),
            'view' => ViewWorkDivisionCatalog::route('/{record}'),
            'edit' => EditWorkDivisionCatalog::route('/{record}/edit'),
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
