<?php

namespace App\Filament\Resources\CompanyActivityResource\Pages;

use App\Filament\Resources\CompanyActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyActivities extends ListRecords
{
    protected static string $resource = CompanyActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create action is handled in table headerActions
        ];
    }
}

