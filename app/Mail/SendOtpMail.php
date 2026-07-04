<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;
    public $otpCode;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin, string $otpCode)
    {
        $this->hotelAdmin = $hotelAdmin;
        $this->otpCode = $otpCode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset OTP - Hotel Portal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
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
