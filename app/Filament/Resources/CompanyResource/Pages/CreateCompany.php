<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::user()?->hasRole('Admin') ?? false;
    }
}

