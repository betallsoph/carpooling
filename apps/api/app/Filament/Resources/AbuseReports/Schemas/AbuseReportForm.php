<?php

namespace App\Filament\Resources\AbuseReports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AbuseReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reporter_id')
                    ->relationship('reporter', 'name')
                    ->required(),
                Select::make('reported_user_id')
                    ->relationship('reportedUser', 'name')
                    ->required(),
                Select::make('trip_instance_id')
                    ->relationship('tripInstance', 'id'),
                TextInput::make('reason')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('evidence_photo_url')
                    ->url(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('resolution_notes')
                    ->columnSpanFull(),
                TextInput::make('resolved_by')
                    ->numeric(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
