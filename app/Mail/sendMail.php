<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email;
    public $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $comments)
    {
        $this->name = $name;
        $this->email = $email;
        $this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->name;
        $email = $this->email;
        $comments = $this->comments;
        return $this->markdown('email.message', compact('name', 'email', 'comments'));
    }
}
