<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::user()?->hasRole('Admin') ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

