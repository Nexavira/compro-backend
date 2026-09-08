<?php

namespace App\Mail;

use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantSuspendedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Tenant $tenant;
    public User $user;

    public function __construct(Tenant $tenant, User $user)
    {
        $this->tenant = $tenant;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Layanan Ditangguhkan - Tagihan ' . config('app.name') . ' Belum Dibayar',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant_suspended',
        );
    }
}
