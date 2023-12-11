<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleAppointment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reschedule Appointment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user.reschedule',
            with: [
                'name' => $this->content['name'], 
                'date' => $this->content['date'], 
                'time' => $this->content['time'],
                'charges' => $this->content['charges'],
                'location' => $this->content['location'],
                'payment_mode' => $this->content['payment_mode'],
                'clinicianImage' => $this->content['clinicianImage'],
                'clinicianName' => $this->content['clinicianName'],
                'email' => $this->content['email'],
                'qualification' => $this->content['qualification'],
                'phone_no' => $this->content['phone_no']
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
