<?php

namespace App\Filament\Resources\AbuseReports\Pages;

use App\Filament\Resources\AbuseReports\AbuseReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAbuseReports extends ListRecords
{
    protected static string $resource = AbuseReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
