<?php

namespace App\Jobs;

use App\Mail\ContactFormMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactFormEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $messageBody,
    ) {}

    public function handle(): void
    {
        $recipient = config('mail.contact_recipient');

        if (!$recipient) {
            return;
        }

        Mail::to($recipient)->send(
            new ContactFormMail(
                senderName: $this->senderName,
                senderEmail: $this->senderEmail,
                messageBody: $this->messageBody,
            )
        );
    }
}
