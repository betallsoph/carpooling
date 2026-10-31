<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('trip_instance_id')
                    ->relationship('tripInstance', 'id')
                    ->required(),
                Select::make('passenger_id')
                    ->relationship('passenger', 'name')
                    ->required(),
                TextInput::make('seats_requested')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('pickup_name')
                    ->required(),
                Textarea::make('pickup_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('dropoff_name')
                    ->required(),
                Textarea::make('dropoff_address')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('requested'),
                TextInput::make('calculated_contribution')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('detour_minutes_added')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('note')
                    ->columnSpanFull(),
                TextInput::make('rejection_reason'),
                DateTimePicker::make('accepted_at'),
                DateTimePicker::make('cancelled_at'),
            ]);
    }
}
