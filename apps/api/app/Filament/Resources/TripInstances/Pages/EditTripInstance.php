<?php

namespace App\Filament\Resources\TripInstances\Pages;

use App\Filament\Resources\TripInstances\TripInstanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTripInstance extends EditRecord
{
    protected static string $resource = TripInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
