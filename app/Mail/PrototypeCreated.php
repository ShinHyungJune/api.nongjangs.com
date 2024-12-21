<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrototypeCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    protected $prototype;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Prototype $prototype)
    {
        $this->prototype = $prototype;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.prototypes.created')
            ->subject("[".config("app.name")."] 시안제작이 완료되었습니다.")
            ->with(["prototype" => $this->prototype]);
    }
}
