<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CustomNotification extends Notification
{
    use Queueable;

    public $subject;
    public $msg;
    public $link;
    public $type;
    public $booking_id;

    public function __construct(Array $data)
    {
        $this->subject = $data["subject"];
        $this->msg = $data["msg"];
        $this->link = $data["link"];
        $this->type = !isset($data["type"]) ? '' : $data["type"];
        $this->booking_id =!isset($data["booking_id"]) ? '' : $data["booking_id"];

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => $this->subject,
            'msg' => $this->msg,
            'link' => $this->link,
            'notifytype' => $this->type,
            'booking_id' => $this->booking_id,
            'count' => $notifiable->unreadNotifications->count(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'subject' => $this->subject,
            'msg' => $this->msg,
            'link' => $this->link,
            'notifytype' => $this->type,
            'booking_id' => $this->booking_id,
            'count' => $notifiable->unreadNotifications->count(),
        ]);
    }
}
