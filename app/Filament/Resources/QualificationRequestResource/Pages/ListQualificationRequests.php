<?php

namespace App\Filament\Resources\QualificationRequestResource\Pages;

use App\Filament\Resources\QualificationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQualificationRequests extends ListRecords
{
    protected static string $resource = QualificationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create action is handled in table headerActions
        ];
    }
}

