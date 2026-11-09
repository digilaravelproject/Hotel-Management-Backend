<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;

class SubscriptionExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin)
    {
        $this->hotelAdmin = $hotelAdmin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service Locked: Your PaxTV Subscription Has Expired - ' . $this->hotelAdmin->hotel_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_expired',
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

