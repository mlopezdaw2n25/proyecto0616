<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * URL completa amb el token en text pla i l'email com a query param.
     * El token en text pla NOMÉS viatja per correu; a la BD es guarda el hash.
     */
    public string $verifyUrl;

    public function __construct(
        private readonly string $token,
        private readonly string $email
    ) {
        $this->verifyUrl = url('/verify-email/' . $this->token . '?email=' . urlencode($this->email));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirma el teu compte – ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'verifyUrl' => $this->verifyUrl,
                'appName'   => config('app.name'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
