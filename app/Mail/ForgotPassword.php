<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    /**
     * Create a new message instance.
     *
     * @param  array  $mailData
     * @param  string $imagePath
     * @param  string $pdfPath
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.forgot-password')
            ->subject('User forgot password');
    }

}
