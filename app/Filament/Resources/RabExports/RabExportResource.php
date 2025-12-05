<?php

namespace App\Filament\Resources\RabExports;

use App\Filament\Resources\RabExports\Pages\CreateRabExport;
use App\Filament\Resources\RabExports\Pages\EditRabExport;
use App\Filament\Resources\RabExports\Pages\ListRabExports;
use App\Filament\Resources\RabExports\Pages\ViewRabExport;
use App\Filament\Resources\RabExports\Schemas\RabExportForm;
use App\Filament\Resources\RabExports\Schemas\RabExportInfolist;
use App\Filament\Resources\RabExports\Tables\RabExportsTable;
use App\Models\RabExport;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RabExportResource extends Resource
{
    protected static ?string $model = RabExport::class;

    public static function form(Schema $schema): Schema
    {
        return RabExportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RabExportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RabExportsTable::configure($table);
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
            'index' => ListRabExports::route('/'),
            'create' => CreateRabExport::route('/create'),
            'view' => ViewRabExport::route('/{record}'),
            'edit' => EditRabExport::route('/{record}/edit'),
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
        return 'Workspace';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-arrow-down-tray';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

}
