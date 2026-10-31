<?php

namespace App\Filament\Resources\ContributionRecords\Pages;

use App\Filament\Resources\ContributionRecords\ContributionRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContributionRecords extends ListRecords
{
    protected static string $resource = ContributionRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
