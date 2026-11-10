<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\HotelAdmin;
use Illuminate\Support\Facades\URL;

class HotelRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hotelAdmin;
    public $plainPassword;
    public string $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(HotelAdmin $hotelAdmin, string $plainPassword)
    {
        $this->hotelAdmin = $hotelAdmin;
        $this->plainPassword = $plainPassword;

        // Generate authentic, tamper-proof signed URL valid for exactly 7 days
        $this->downloadUrl = URL::temporarySignedRoute(
            'app.apk.download',
            now()->addDays(7),
            ['ref' => 'hotel', 'hotel' => $hotelAdmin->id]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hotel Registration Successful - ' . $this->hotelAdmin->hotel_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hotel_registered',
            with: [
                'downloadUrl' => $this->downloadUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
