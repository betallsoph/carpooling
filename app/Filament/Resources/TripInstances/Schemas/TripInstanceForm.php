<?php

namespace App\Filament\Resources\TripInstances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class TripInstanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('trip_template_id')
                    ->numeric(),
                Select::make('driver_profile_id')
                    ->relationship('driverProfile', 'id')
                    ->required(),
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('departure_time')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('scheduled'),
                TextInput::make('origin_name')
                    ->required(),
                Textarea::make('origin_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('destination_name')
                    ->required(),
                Textarea::make('destination_address')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('route_polyline')
                    ->columnSpanFull(),
                TextInput::make('total_distance_km')
                    ->required()
                    ->numeric(),
                TextInput::make('estimated_duration_minutes')
                    ->required()
                    ->numeric(),
                TextInput::make('toll_fee')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('fuel_rate_per_km')
                    ->required()
                    ->numeric()
                    ->default(1800),
                TextInput::make('total_seats')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('available_seats')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('max_detour_minutes')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('current_cost_per_person')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('cancellation_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
