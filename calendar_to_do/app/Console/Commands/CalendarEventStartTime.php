<?php

namespace App\Console\Commands;

use App\Consts\NotificationConst;
use App\Models\Calendar;
use App\Models\User;
use App\Notifications\CalendarNotice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalendarEventStartTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event-start-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'イベントの開始5前にメールを送信する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $eventDate = (Carbon::now())->addMinute(5);
        $eventDate->second = 0;
        $noticeEvents = Calendar::where('start', $eventDate)->get()->toArray();

        logger($noticeEvents);
        foreach ($noticeEvents as $noticeEvent) {
            $user = User::find($noticeEvent['user_id']);
            $user->notify(new CalendarNotice(NotificationConst::CALENDAR_MAIL_BATCH, $noticeEvent['name'], $noticeEvent['start'], $noticeEvent['end'], $user));
        }
    }
}
