<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tripInstance.id')
                    ->searchable(),
                TextColumn::make('passenger.name')
                    ->searchable(),
                TextColumn::make('seats_requested')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_name')
                    ->searchable(),
                TextColumn::make('dropoff_name')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('calculated_contribution')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('detour_minutes_added')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rejection_reason')
                    ->searchable(),
                TextColumn::make('accepted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
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
