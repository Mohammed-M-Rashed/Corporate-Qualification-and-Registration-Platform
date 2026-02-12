<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Services\CommitteeService;
use Filament\Resources\Pages\CreateRecord;

class CreateCommittee extends CreateRecord
{
    protected static string $resource = CommitteeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $committeeService = app(CommitteeService::class);
        
        // If activating this committee, deactivate others of the same type
        if (isset($data['is_active']) && $data['is_active']) {
            \App\Models\Committee::where('committee_type_id', $data['committee_type_id'])
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Members are automatically synced by Filament when using relationship() with multiple()
        // But we ensure it's done explicitly
        $data = $this->form->getState();
        if (isset($data['members']) && is_array($data['members'])) {
            $this->record->members()->sync($data['members']);
        }
    }
}
