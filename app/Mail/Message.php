<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class Message extends Mailable
{
    private $name;
    private $email;
    private $body;

    /**
     * Message constructor.
     * @param string $name
     * @param string $email
     * @param string $body
     */
    public function __construct(string $name, string $email, string $body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.message', ['name' => $this->name, 'email' => $this->email, 'body' => $this->body]);
    }
}
