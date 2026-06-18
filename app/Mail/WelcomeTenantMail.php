<?php

namespace App\Mail;

use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeTenantMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $tenant;
    public User $user;
    public string $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Tenant $tenant, User $user, string $resetUrl)
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di ' . config('app.name') . ' - Setup Akun Anda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome_tenant',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
