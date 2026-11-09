<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;

class HotelWelcomeCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;
    public $plainPassword;
    public $creatorType;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin, string $plainPassword, string $creatorType = 'Super Admin')
    {
        $this->hotelAdmin = $hotelAdmin;
        $this->plainPassword = $plainPassword;
        $this->creatorType = $creatorType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to PaxTV Hospitality - Portal Access for ' . $this->hotelAdmin->hotel_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hotel_welcome_credentials',
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

