<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CalendarNotice extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $view, string $name, string $startDate, string $endDate, $user)
    {
        $this->view = $view;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userName = $user->name;
        $this->userEmail = $user->email;
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
        $sendData = [
            'name'  => $this->name,
            'startDate'  => $this->startDate,
            'endDate'  => $this->endDate,
            'user'  => $this->userName,
        ];

        return (new MailMessage)->markdown($this->view, $sendData);
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
