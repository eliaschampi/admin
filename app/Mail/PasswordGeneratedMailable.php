<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordGeneratedMailable extends Mailable
{
    use Queueable, SerializesModels;

    protected string $generatedPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $generatedPassword)
    {
        $this->generatedPassword = $generatedPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password')
            ->subject("Notificación de seguridad")
            ->with([
                "generatedPassword" => $this->generatedPassword
            ]);
    }
}
