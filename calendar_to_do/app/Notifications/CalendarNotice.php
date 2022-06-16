<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Calendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class CalendarNotice extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $view, Calendar $calendar, User $user)
    {
        $this->view = $view;
        $this->name = $calendar->name;
        $this->startDate = $calendar->start;
        $this->endDate = $calendar->end;
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
        if(!\App::environment('local')){
            return ['slack'];
        }
        return ['slack', 'mail'];
    }

    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->from($this->userName)
            ->content("{$this->name}が{$this->startDate}〜{$this->endDate}に設定されました。");
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
