<?php

namespace App\Filament\Resources\DriverProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriverProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('license_number')
                    ->searchable(),
                TextColumn::make('license_class')
                    ->searchable(),
                ImageColumn::make('license_front_image'),
                ImageColumn::make('license_back_image'),
                TextColumn::make('verification_status')
                    ->searchable(),
                TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->searchable(),
                TextColumn::make('bank_code')
                    ->searchable(),
                TextColumn::make('bank_account_no')
                    ->searchable(),
                TextColumn::make('bank_account_name')
                    ->searchable(),
                TextColumn::make('rating_avg')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_rides_completed')
                    ->numeric()
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
