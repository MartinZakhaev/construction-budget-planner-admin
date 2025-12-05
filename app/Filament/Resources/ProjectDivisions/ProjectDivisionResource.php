<?php

namespace App\Filament\Resources\ProjectDivisions;

use App\Filament\Resources\ProjectDivisions\Pages\CreateProjectDivision;
use App\Filament\Resources\ProjectDivisions\Pages\EditProjectDivision;
use App\Filament\Resources\ProjectDivisions\Pages\ListProjectDivisions;
use App\Filament\Resources\ProjectDivisions\Pages\ViewProjectDivision;
use App\Filament\Resources\ProjectDivisions\Schemas\ProjectDivisionForm;
use App\Filament\Resources\ProjectDivisions\Schemas\ProjectDivisionInfolist;
use App\Filament\Resources\ProjectDivisions\Tables\ProjectDivisionsTable;
use App\Models\ProjectDivision;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectDivisionResource extends Resource
{
    protected static ?string $model = ProjectDivision::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProjectDivisionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectDivisionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectDivisionsTable::configure($table);
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
            'index' => ListProjectDivisions::route('/'),
            'create' => CreateProjectDivision::route('/create'),
            'view' => ViewProjectDivision::route('/{record}'),
            'edit' => EditProjectDivision::route('/{record}/edit'),
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
