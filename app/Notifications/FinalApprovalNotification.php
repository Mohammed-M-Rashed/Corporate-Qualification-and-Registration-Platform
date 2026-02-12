<?php

namespace App\Notifications;

use App\Models\QualificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalApprovalNotification extends Notification
{
    use Queueable;

    public function __construct(
        public QualificationRequest $request
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
            'message' => 'تم قبول الطلب نهائياً: ' . $this->request->request_number,
            'url' => route('filament.admin.resources.qualification-requests.view', $this->request),
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'request_number' => $this->request->request_number,
            'company_name' => $this->request->company->name,
        ];
    }
}

