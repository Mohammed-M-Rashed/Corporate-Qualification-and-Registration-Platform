<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Services\CommitteeService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommittee extends EditRecord
{
    protected static string $resource = CommitteeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $committee = $this->record;
        
        // If activating this committee, deactivate others of the same type
        if (isset($data['is_active']) && $data['is_active'] && !$committee->is_active) {
            \App\Models\Committee::where('committee_type_id', $committee->committee_type_id)
                ->where('id', '!=', $committee->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Members are automatically synced by Filament when using relationship() with multiple()
        // But we ensure it's done explicitly
        $data = $this->form->getState();
        if (isset($data['members']) && is_array($data['members'])) {
            $this->record->members()->sync($data['members']);
        }
    }
}
