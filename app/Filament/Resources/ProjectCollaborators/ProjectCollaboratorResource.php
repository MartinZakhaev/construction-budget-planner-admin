<?php

namespace App\Filament\Resources\ProjectCollaborators;

use App\Filament\Resources\ProjectCollaborators\Pages\CreateProjectCollaborator;
use App\Filament\Resources\ProjectCollaborators\Pages\EditProjectCollaborator;
use App\Filament\Resources\ProjectCollaborators\Pages\ListProjectCollaborators;
use App\Filament\Resources\ProjectCollaborators\Pages\ViewProjectCollaborator;
use App\Filament\Resources\ProjectCollaborators\Schemas\ProjectCollaboratorForm;
use App\Filament\Resources\ProjectCollaborators\Schemas\ProjectCollaboratorInfolist;
use App\Filament\Resources\ProjectCollaborators\Tables\ProjectCollaboratorsTable;
use App\Models\ProjectCollaborator;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectCollaboratorResource extends Resource
{
    protected static ?string $model = ProjectCollaborator::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProjectCollaboratorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectCollaboratorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectCollaboratorsTable::configure($table);
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
            'index' => ListProjectCollaborators::route('/'),
            'create' => CreateProjectCollaborator::route('/create'),
            'view' => ViewProjectCollaborator::route('/{record}'),
            'edit' => EditProjectCollaborator::route('/{record}/edit'),
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
