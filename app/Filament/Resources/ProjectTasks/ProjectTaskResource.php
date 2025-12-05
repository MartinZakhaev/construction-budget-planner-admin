<?php

namespace App\Filament\Resources\ProjectTasks;

use App\Filament\Resources\ProjectTasks\Pages\CreateProjectTask;
use App\Filament\Resources\ProjectTasks\Pages\EditProjectTask;
use App\Filament\Resources\ProjectTasks\Pages\ListProjectTasks;
use App\Filament\Resources\ProjectTasks\Pages\ViewProjectTask;
use App\Filament\Resources\ProjectTasks\Schemas\ProjectTaskForm;
use App\Filament\Resources\ProjectTasks\Schemas\ProjectTaskInfolist;
use App\Filament\Resources\ProjectTasks\Tables\ProjectTasksTable;
use App\Models\ProjectTask;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectTaskResource extends Resource
{
    protected static ?string $model = ProjectTask::class;

    public static function form(Schema $schema): Schema
    {
        return ProjectTaskForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectTaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectTasksTable::configure($table);
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
            'index' => ListProjectTasks::route('/'),
            'create' => CreateProjectTask::route('/create'),
            'view' => ViewProjectTask::route('/{record}'),
            'edit' => EditProjectTask::route('/{record}/edit'),
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
        return 'heroicon-o-clipboard-document-check';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

}
