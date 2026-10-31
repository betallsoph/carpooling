<?php

namespace App\Filament\Resources\TripInstances\Pages;

use App\Filament\Resources\TripInstances\TripInstanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTripInstances extends ListRecords
{
    protected static string $resource = TripInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
