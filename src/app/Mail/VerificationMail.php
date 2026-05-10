<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerificationMail extends Mailable
{
    public string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('メール認証コード')
            ->view('emails.verification')
            ->with([
                'code' => $this->code,
            ]);
    }
}
