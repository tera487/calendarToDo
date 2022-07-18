<?php

namespace App\Listeners;

use App\Consts\NotificationConst;
use App\Event\NotifyUserSchedule;
use App\Models\User;
use App\Notifications\CalendarNotice;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class SendScheduleEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\NotifyUserSchedule  $event
     * @return void
     */
    public function handle(NotifyUserSchedule $event)
    {
        try {
            $user = User::find($event->calendar->user_id);
            $user->notify(new CalendarNotice($event->view, $event->calendar, $user));
        } catch (Exception $e) {
            logger($e);
        }
    }
}
