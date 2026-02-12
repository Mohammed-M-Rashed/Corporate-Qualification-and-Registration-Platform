<?php

namespace App\Filament\Resources\QualificationRequestResource\Pages;

use App\Filament\Resources\QualificationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQualificationRequest extends EditRecord
{
    protected static string $resource = QualificationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

