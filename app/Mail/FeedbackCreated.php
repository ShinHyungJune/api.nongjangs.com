<?php

namespace App\Mail;

use App\Models\Feedback;
use App\Models\PasswordReset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    protected $feedback;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.feedbacks.created')
            ->subject("[".config("app.name")."] 시안피드백이 접수되었습니다.")
            ->with(["feedback" => $this->feedback]);
    }
}
