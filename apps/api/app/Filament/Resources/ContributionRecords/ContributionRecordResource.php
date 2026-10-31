<?php

namespace App\Filament\Resources\ContributionRecords;

use App\Filament\Resources\ContributionRecords\Pages\CreateContributionRecord;
use App\Filament\Resources\ContributionRecords\Pages\EditContributionRecord;
use App\Filament\Resources\ContributionRecords\Pages\ListContributionRecords;
use App\Filament\Resources\ContributionRecords\Schemas\ContributionRecordForm;
use App\Filament\Resources\ContributionRecords\Tables\ContributionRecordsTable;
use App\Models\ContributionRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContributionRecordResource extends Resource
{
    protected static ?string $model = ContributionRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ContributionRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContributionRecordsTable::configure($table);
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
            'index' => ListContributionRecords::route('/'),
            'create' => CreateContributionRecord::route('/create'),
            'edit' => EditContributionRecord::route('/{record}/edit'),
        ];
    }
}
