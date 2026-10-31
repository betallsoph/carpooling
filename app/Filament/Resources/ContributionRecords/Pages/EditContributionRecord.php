<?php

namespace App\Filament\Resources\ContributionRecords\Pages;

use App\Filament\Resources\ContributionRecords\ContributionRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContributionRecord extends EditRecord
{
    protected static string $resource = ContributionRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
