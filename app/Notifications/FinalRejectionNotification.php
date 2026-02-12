<?php

namespace App\Notifications;

use App\Models\QualificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalRejectionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public QualificationRequest $request,
        public string $reason
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'request_number' => $this->request->request_number,
            'company_name' => $this->request->company->name,
            'reason' => $this->reason,
            'message' => 'تم رفض الطلب نهائياً: ' . $this->request->request_number . ' - السبب: ' . $this->reason,
            'url' => route('filament.admin.resources.qualification-requests.view', $this->request),
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'request_number' => $this->request->request_number,
            'company_name' => $this->request->company->name,
            'reason' => $this->reason,
        ];
    }
}

