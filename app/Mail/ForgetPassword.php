<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token,$username)
    {
        $this->token = $token;
        $this->username = $username;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Forget Password',
        );
    }

    public function build()
    {
        return $this->view('email.forget-password');
    }

}
