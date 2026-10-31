<?php

namespace App\Filament\Resources\AbuseReports;

use App\Filament\Resources\AbuseReports\Pages\CreateAbuseReport;
use App\Filament\Resources\AbuseReports\Pages\EditAbuseReport;
use App\Filament\Resources\AbuseReports\Pages\ListAbuseReports;
use App\Filament\Resources\AbuseReports\Schemas\AbuseReportForm;
use App\Filament\Resources\AbuseReports\Tables\AbuseReportsTable;
use App\Models\AbuseReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AbuseReportResource extends Resource
{
    protected static ?string $model = AbuseReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AbuseReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AbuseReportsTable::configure($table);
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
            'index' => ListAbuseReports::route('/'),
            'create' => CreateAbuseReport::route('/create'),
            'edit' => EditAbuseReport::route('/{record}/edit'),
        ];
    }
}
