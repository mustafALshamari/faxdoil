<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SalonInvitation extends Notification
{
    use Queueable;

    protected $salon;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($salon)
    {
        $this->salon = $salon;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/api/stylist/find/salon/'.$this->salon);

        return (new MailMessage)
                    ->line('You Have Salon Invitation.')
                    ->action('Accept invitation', url($url))
                    ->line('congratulations!');
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
            //
        ];
    }
}
