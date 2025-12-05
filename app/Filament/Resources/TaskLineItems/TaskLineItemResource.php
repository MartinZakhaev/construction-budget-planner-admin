<?php

namespace App\Filament\Resources\TaskLineItems;

use App\Filament\Resources\TaskLineItems\Pages\CreateTaskLineItem;
use App\Filament\Resources\TaskLineItems\Pages\EditTaskLineItem;
use App\Filament\Resources\TaskLineItems\Pages\ListTaskLineItems;
use App\Filament\Resources\TaskLineItems\Pages\ViewTaskLineItem;
use App\Filament\Resources\TaskLineItems\Schemas\TaskLineItemForm;
use App\Filament\Resources\TaskLineItems\Schemas\TaskLineItemInfolist;
use App\Filament\Resources\TaskLineItems\Tables\TaskLineItemsTable;
use App\Models\TaskLineItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskLineItemResource extends Resource
{
    protected static ?string $model = TaskLineItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TaskLineItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TaskLineItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaskLineItemsTable::configure($table);
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
            'index' => ListTaskLineItems::route('/'),
            'create' => CreateTaskLineItem::route('/create'),
            'view' => ViewTaskLineItem::route('/{record}'),
            'edit' => EditTaskLineItem::route('/{record}/edit'),
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
