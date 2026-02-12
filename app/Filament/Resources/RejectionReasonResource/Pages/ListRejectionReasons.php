<?php

namespace App\Filament\Resources\RejectionReasonResource\Pages;

use App\Filament\Resources\RejectionReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRejectionReasons extends ListRecords
{
    protected static string $resource = RejectionReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create action is handled in table headerActions
        ];
    }
}

