<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;
    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Example: dynamic subject and replyTo from incoming contact data
        $fromAddress = $this->data['email'] ?? null;
        $fromName = trim(($this->data['first_name'] ?? '') . ' ' . ($this->data['last_name'] ?? ''));
        $displaySubject = ($this->data['subject'] ?? 'New Contact Message') . ($fromAddress ? " from {$fromAddress}" : "");

        $replyTo = $fromAddress
            ? [new Address($fromAddress, $fromName ?: null)]
            : [];

        return new Envelope(
            subject: $displaySubject,
            replyTo: $replyTo,
        );
        // return new Envelope(
        //     subject: 'Contact',
        // );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.contact',

            with: [
                'data' => $this->data,
                'settings' => \App\Models\GeneralSetting::first(),
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
