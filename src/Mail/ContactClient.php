<?php

namespace Leobsst\LaravelCmsCore\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactClient extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $name, public $subject, public $contentMessage)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirmation de l\'envoi de votre message');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'laravel-cms-core::mail.contact-mail-client',
            with: [
                'name' => $this->name,
                'subject' => $this->subject,
                'contentMessage' => $this->contentMessage,
            ],
        );
    }
}
