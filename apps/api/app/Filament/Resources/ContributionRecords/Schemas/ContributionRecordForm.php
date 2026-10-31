<?php

namespace App\Filament\Resources\ContributionRecords\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContributionRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->required(),
                Select::make('trip_instance_id')
                    ->relationship('tripInstance', 'id')
                    ->required(),
                Select::make('passenger_id')
                    ->relationship('passenger', 'name')
                    ->required(),
                Select::make('driver_profile_id')
                    ->relationship('driverProfile', 'id')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('transfer_content')
                    ->required(),
                Textarea::make('vietqr_url')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('passenger_confirmed_at'),
                DateTimePicker::make('driver_confirmed_at'),
                FileUpload::make('proof_image_url')
                    ->image(),
                Textarea::make('dispute_reason')
                    ->columnSpanFull(),
                Textarea::make('admin_resolution_notes')
                    ->columnSpanFull(),
            ]);
    }
}
