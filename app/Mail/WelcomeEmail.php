<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tenant;
    public $user;
    public $resetUrl;

    public function __construct($tenant = null, $user = null, $resetUrl = '#')
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di ' . config('app.name') . ' - Setup Akun Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', 
        );
    }
}
