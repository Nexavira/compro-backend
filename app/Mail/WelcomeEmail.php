<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Sangat disarankan implement ShouldQueue agar request user tidak terblokir saat API call ke Resend
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
            view: 'emails.welcome', // Sesuaikan dengan file blade Anda
        );
    }
}
