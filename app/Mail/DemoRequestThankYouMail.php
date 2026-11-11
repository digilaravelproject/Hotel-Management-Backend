<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use App\Models\ContactInquiry;

class DemoRequestThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public ContactInquiry $inquiry;
    public string $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactInquiry $inquiry)
    {
        $this->inquiry = $inquiry;

        if (config('app.env') !== 'local' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        // Generate authentic, tamper-proof signed URL valid for exactly 7 days
        $this->downloadUrl = URL::temporarySignedRoute(
            'app.apk.download',
            now()->addDays(7),
            ['ref' => 'lead', 'inquiry' => $inquiry->id]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank You for Requesting a Demo | Pax TV Launcher & App Access',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.demo_request_thankyou',
            with: [
                'downloadUrl' => $this->downloadUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

