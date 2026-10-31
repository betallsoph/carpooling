<?php

namespace App\Filament\Resources\DriverProfiles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DriverProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('license_number')
                    ->required(),
                TextInput::make('license_class')
                    ->required()
                    ->default('B2'),
                FileUpload::make('license_front_image')
                    ->image(),
                FileUpload::make('license_back_image')
                    ->image(),
                TextInput::make('verification_status')
                    ->required()
                    ->default('pending'),
                Textarea::make('rejection_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('verified_at'),
                TextInput::make('bank_name'),
                TextInput::make('bank_code'),
                TextInput::make('bank_account_no'),
                TextInput::make('bank_account_name'),
                TextInput::make('rating_avg')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('rating_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_rides_completed')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
