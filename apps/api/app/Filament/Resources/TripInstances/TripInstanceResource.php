<?php

namespace App\Filament\Resources\TripInstances;

use App\Filament\Resources\TripInstances\Pages\CreateTripInstance;
use App\Filament\Resources\TripInstances\Pages\EditTripInstance;
use App\Filament\Resources\TripInstances\Pages\ListTripInstances;
use App\Filament\Resources\TripInstances\Schemas\TripInstanceForm;
use App\Filament\Resources\TripInstances\Tables\TripInstancesTable;
use App\Models\TripInstance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TripInstanceResource extends Resource
{
    protected static ?string $model = TripInstance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TripInstanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TripInstancesTable::configure($table);
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
            'index' => ListTripInstances::route('/'),
            'create' => CreateTripInstance::route('/create'),
            'edit' => EditTripInstance::route('/{record}/edit'),
        ];
    }
}
