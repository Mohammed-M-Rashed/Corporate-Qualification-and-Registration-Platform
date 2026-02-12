<?php

namespace App\Mail;

use App\Models\QualificationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public QualificationRequest $request
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم استلام طلب التأهيل',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-received',
            with: [
                'request' => $this->request,
                'company' => $this->request->company,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
