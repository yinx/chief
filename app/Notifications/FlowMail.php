<?php

namespace Thinktomorrow\Chief\App\Notifications;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;



class FlowMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $view;
    public $subject;

    public function __construct($data, $view, $subject)
    {
        $this->data    = $data;
        $this->view    = $view;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
                ->from(config('thinktomorrow.chief.contact.email'))
                ->view($this->view, [
                    'data' => $this->data,
                ]);
    }
}
