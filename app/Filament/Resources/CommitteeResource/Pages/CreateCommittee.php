<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use App\Services\CommitteeService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class CreateCommittee extends CreateRecord
{
    protected static string $resource = CommitteeResource::class;

    protected function getCreateFormAction(): Action
    {
        // استخدام action() بدلاً من submit() حتى يعمل requiresConfirmation
        // لأن submit() يجعل الزر type="submit" ويتجاوز نظام الـ modal
        return Action::make('create')
            ->label('إضافة')
            ->action(fn () => $this->create())
            ->keyBindings(['mod+s'])
            ->requiresConfirmation(function () {
                $data = $this->form->getState();
                
                if (isset($data['is_active']) && $data['is_active'] && isset($data['committee_type_id'])) {
                    return \App\Models\Committee::where('committee_type_id', $data['committee_type_id'])
                        ->where('is_active', true)
                        ->exists();
                }
                
                return false;
            })
            ->modalHeading('تنبيه: يوجد لجنة مفعلة من نفس النوع')
            ->modalDescription(function () {
                $data = $this->form->getState();
                
                if (isset($data['is_active']) && $data['is_active'] && isset($data['committee_type_id'])) {
                    $activeCommittees = \App\Models\Committee::where('committee_type_id', $data['committee_type_id'])
                        ->where('is_active', true)
                        ->get();
                    
                    if ($activeCommittees->isNotEmpty()) {
                        $committeeNames = $activeCommittees->pluck('name')->implode('، ');
                        $committeeType = \App\Models\CommitteeType::find($data['committee_type_id']);
                        $typeName = $committeeType ? $committeeType->name_ar : 'نفس النوع';
                        
                        return 'يوجد ' . $activeCommittees->count() . ' لجنة مفعلة من نفس النوع (' . $typeName . '):' . "\n\n" . $committeeNames . "\n\n" . 'عند المتابعة، سيتم إلغاء تفعيل هذه اللجان تلقائياً واللجنة الجديدة ستكون المفعلة.';
                    }
                }
                
                return 'هل أنت متأكد من القيام بهذه العملية؟';
            })
            ->modalSubmitActionLabel('تأكيد')
            ->modalCancelActionLabel('إلغاء')
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->modalIconColor('warning')
            ->color('success')
            ->modalWidth('md');
    }
    
    protected function getCreateAnotherFormAction(): Action
    {
        // إخفاء زر "إضافة وبدء إضافة المزيد" باستخدام CSS
        return parent::getCreateAnotherFormAction()
            ->hidden();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $committeeService = app(CommitteeService::class);
        
        // If activating this committee, deactivate others of the same type
        if (isset($data['is_active']) && $data['is_active'] && isset($data['committee_type_id'])) {
            $activeCommittees = \App\Models\Committee::where('committee_type_id', $data['committee_type_id'])
                ->where('is_active', true)
                ->get();
            
            if ($activeCommittees->isNotEmpty()) {
                $activeCommittees->each(function ($committee) {
                    $committee->update(['is_active' => false]);
                });
                
                // إشعار للمستخدم
                \Filament\Notifications\Notification::make()
                    ->title('تم إلغاء تفعيل اللجنة السابقة')
                    ->body('تم إلغاء تفعيل ' . $activeCommittees->count() . ' لجنة من نفس النوع تلقائياً.')
                    ->success()
                    ->send();
            }
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
