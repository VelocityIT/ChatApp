<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $ip;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$ip)
    {
        $this->name = $name;
        $this->ip = $ip;
    }

    public function envelope()
    {
        return new Envelope(
            subject: env('APP_NAME') . " - Successful Login",
        );
    }

    public function build()
    {
        return $this->view('email.login_notification');
    }
}
