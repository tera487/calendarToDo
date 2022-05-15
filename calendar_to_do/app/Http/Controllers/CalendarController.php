<?php

namespace App\Http\Controllers;

use App\Consts\NotificationConst;
use App\Models\Calendar;
use App\Event\NotifyUserSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    private function setCalendarArray(array $append = [])
    {
        $initCalendar = ['name', 'start', 'end', 'description', 'is_send'];
        if (!empty($append)) {
            $initCalendar = array_merge($initCalendar, $append);
        }
        return $initCalendar;
    }


    public function index()
    {
        $calendar_items = Calendar::where('user_id', Auth::id())->get($this->setCalendarArray(['id']))->toArray();
        return array_reverse($calendar_items);
    }

    public function store(Calendar $calendar, Request $request)
    {
        $calendar->fill($request->only($this->setCalendarArray()));
        $calendar->user_id = Auth::id();
        $calendar->save();

        event(new NotifyUserSchedule(NotificationConst::CALENDAR_MAIL_CREATE, $calendar));
        return response()->json($calendar->id);
    }

    public function search(Request $request)
    {
        return response()->json(array_reverse(Calendar::where('user_id', Auth::id())->where('name', 'LIKE', '%' . $request->searchItem . '%')->get(
            $this->setCalendarArray(['id', 'updated_at'])
        )->toArray()));
    }

    public function update($id, Request $request)
    {
        $calendar = Calendar::find($id);
        $calendar->fill($request->only($this->setCalendarArray()));
        $calendar->user_id = Auth::id();
        $calendar->save();
        return;
    }

    public function destroy($id)
    {
        $calendar = Calendar::find($id);
        $calendar->delete();

        return response()->json('deleted');
    }
}
