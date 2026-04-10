<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * URL completa amb el token en text pla i l'email com a query param.
     * El token en text pla NOMÉS viatge per correu; a la BD es guarda el hash.
     */
    public string $resetUrl;

    public function __construct(
        private readonly string $token,
        private readonly string $email
    ) {
        $this->resetUrl = url('/reset-password/' . $this->token . '?email=' . urlencode($this->email));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Restabliment de contrasenya – ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'appName'  => config('app.name'),
            ],
        );
    }

    /**
     * Cap fitxer adjunt.
     */
    public function attachments(): array
    {
        return [];
    }
}
