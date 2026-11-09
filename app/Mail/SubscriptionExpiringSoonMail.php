<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;

class SubscriptionExpiringSoonMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;
    public $daysRemaining;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin, int $daysRemaining)
    {
        $this->hotelAdmin = $hotelAdmin;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $dayText = $this->daysRemaining === 1 ? 'Tomorrow' : "in {$this->daysRemaining} Days";
        return new Envelope(
            subject: "Urgent Renewal Alert: PaxTV Subscription Expires {$dayText} - {$this->hotelAdmin->hotel_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_expiring_soon',
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
