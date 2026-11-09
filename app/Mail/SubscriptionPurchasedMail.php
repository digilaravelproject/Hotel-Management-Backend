<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;
use App\Models\Plan;

class SubscriptionPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;
    public $plan;
    public $context;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin, Plan $plan, array $context = [])
    {
        $this->hotelAdmin = $hotelAdmin;
        $this->plan = $plan;
        $this->context = $context;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $action = ucfirst($this->context['action'] ?? 'Activated');
        return new Envelope(
            subject: "Subscription {$action}: {$this->plan->name} - {$this->hotelAdmin->hotel_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription_purchased',
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
