<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipientEmail;
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct(array|string $recipientOrDetails, array $smtpDetails = [])
    {
        if (is_array($recipientOrDetails)) {
            $this->details = $recipientOrDetails;
            $this->recipientEmail = $recipientOrDetails['recipient'] ?? $recipientOrDetails['to'] ?? '';
        } else {
            $this->recipientEmail = $recipientOrDetails;
            $this->details = $smtpDetails;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PaxTV Platform - SMTP Connection Test Successful',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test_mail',
            with: [
                'details' => $this->details,
                'recipientEmail' => $this->recipientEmail,
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

