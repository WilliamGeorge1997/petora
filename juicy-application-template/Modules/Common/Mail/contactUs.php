<?php

namespace Modules\Common\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contactUs extends Mailable
{
    use Queueable, SerializesModels;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($phone,$email, $message)
    {
        $this->phone = $phone;

        $this->email = $email;

        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from($this->email)
        ->markdown('common::Email.ContactUs')->with([
            'phone' => $this->phone,
            'email' => $this->email,
            'message' => $this->message,
        ]);
    }
}
