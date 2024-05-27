<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
       // $this->verification_token = $token;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email')->view('session.email-verification');
    }
}
