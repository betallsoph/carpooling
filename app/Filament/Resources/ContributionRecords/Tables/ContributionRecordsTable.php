<?php

namespace App\Filament\Resources\ContributionRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContributionRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.id')
                    ->searchable(),
                TextColumn::make('tripInstance.id')
                    ->searchable(),
                TextColumn::make('passenger.name')
                    ->searchable(),
                TextColumn::make('driverProfile.id')
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transfer_content')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('passenger_confirmed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('driver_confirmed_at')
                    ->dateTime()
                    ->sortable(),
                ImageColumn::make('proof_image_url'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
