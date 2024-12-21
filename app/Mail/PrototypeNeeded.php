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

class PrototypeNeeded extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    protected $presetProduct;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PresetProduct $presetProduct)
    {
        $this->presetProduct = $presetProduct;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.prototypes.needed')
            ->subject("[".config("app.name")."] 시안제작요청이 접수되었습니다.")
            ->with([ "presetProduct" => $this->presetProduct]);
    }
}
