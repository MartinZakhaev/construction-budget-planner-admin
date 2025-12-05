<?php

namespace App\Filament\Resources\ItemCatalogs;

use App\Filament\Resources\ItemCatalogs\Pages\CreateItemCatalog;
use App\Filament\Resources\ItemCatalogs\Pages\EditItemCatalog;
use App\Filament\Resources\ItemCatalogs\Pages\ListItemCatalogs;
use App\Filament\Resources\ItemCatalogs\Pages\ViewItemCatalog;
use App\Filament\Resources\ItemCatalogs\Schemas\ItemCatalogForm;
use App\Filament\Resources\ItemCatalogs\Schemas\ItemCatalogInfolist;
use App\Filament\Resources\ItemCatalogs\Tables\ItemCatalogsTable;
use App\Models\ItemCatalog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemCatalogResource extends Resource
{
    protected static ?string $model = ItemCatalog::class;

    public static function form(Schema $schema): Schema
    {
        return ItemCatalogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemCatalogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemCatalogsTable::configure($table);
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
            'index' => ListItemCatalogs::route('/'),
            'create' => CreateItemCatalog::route('/create'),
            'view' => ViewItemCatalog::route('/{record}'),
            'edit' => EditItemCatalog::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function getNavigationGroup(): ?string
    {
        return 'Master Catalogs';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cube';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

}
