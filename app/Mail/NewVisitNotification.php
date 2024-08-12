<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewVisitNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $visit;

    /**
     * Create a new message instance.
     *
     * @param $visit
     */
    public function __construct($visit)
    {
        $this->visit = $visit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Visit Scheduled')
                    ->view('emails.visit_mail');
    }
}
