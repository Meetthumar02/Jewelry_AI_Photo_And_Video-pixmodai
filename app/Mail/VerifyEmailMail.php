<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verifyLink;

    public function __construct($user, $verifyLink)
    {
        $this->user = $user;
        $this->verifyLink = $verifyLink;
    }

    public function build()
    {
        return $this->subject('Verify Your Account - JW AI')
                    ->view('emails.verify-email')
                    ->with([
                        'user'       => $this->user,
                        'verifyLink'=> $this->verifyLink,
                    ]);
    }
}
