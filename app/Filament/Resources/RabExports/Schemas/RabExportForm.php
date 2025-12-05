<?php

namespace App\Filament\Resources\RabExports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RabExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rab_summary_id')
                    ->required(),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Select::make('pdf_file_id')
                    ->relationship('pdfFile', 'id'),
                Select::make('xlsx_file_id')
                    ->relationship('xlsxFile', 'id'),
            ]);
    }
}
