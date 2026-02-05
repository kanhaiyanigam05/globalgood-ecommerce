<?php

namespace App\Mail;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;
    public $updateType;

    /**
     * Create a new message instance.
     */
    public function __construct(Vendor $vendor, string $updateType)
    {
        $this->vendor = $vendor;
        $this->updateType = $updateType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vendor Update Notification: ' . $this->updateType,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.vendors.update_notification',
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
