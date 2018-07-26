<?php

namespace Thinktomorrow\Chief\App\Notifications;

use Thinktomorrow\Chief\Users\Invites\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class FlowMail extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $view;

    public function __construct($data, $view)
    {
        $this->data = $data;
        $this->view = $view;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject($this->data['subject'])
            ->from(config('thinktomorrow.chief.contact.email'))
            ->view($this->view, [
                'data' => $this->data,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
