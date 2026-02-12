<?php

namespace App\Mail;

use App\Models\QualificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public QualificationRequest $request,
        public string $reason
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم رفض طلب التأهيل',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-rejected',
            with: [
                'request' => $this->request,
                'company' => $this->request->company,
                'reason' => $this->reason,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
