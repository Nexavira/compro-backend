<?php

namespace App\Mail;

use App\Models\Auth\User;
use App\Models\Tenant\Tenant;
use App\Models\Transaction\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Tenant $tenant;
    public User $user;
    public Payment $payment;
    public int $daysLeft;

    public function __construct(Tenant $tenant, User $user, Payment $payment, int $daysLeft)
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->payment = $payment;
        $this->daysLeft = $daysLeft;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Peringatan Pembayaran: Tagihan Layanan {$this->tenant->name} Jatuh Tempo dalam {$this->daysLeft} Hari",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_reminder',
        );
    }
}
