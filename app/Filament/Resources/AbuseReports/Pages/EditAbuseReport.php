<?php

namespace App\Filament\Resources\AbuseReports\Pages;

use App\Filament\Resources\AbuseReports\AbuseReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAbuseReport extends EditRecord
{
    protected static string $resource = AbuseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
