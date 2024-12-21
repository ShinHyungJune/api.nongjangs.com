<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sample extends Mailable
{
    use Queueable, SerializesModels;


    protected $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( PasswordReset $item)
    {
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.passwordResets.created')
            ->subject("[".config("app.name")."] ".__("comment.passwordResets.201"))
            ->with([ "item" => $this->item]);
    }
}
