<?php

namespace App\Http\Controllers;

use App\Consts\NotificationConst;
use App\Models\Calendar;
use App\Event\NotifyUserSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $calendar_items = Calendar::where('user_id', Auth::id())->get(['name', 'start', 'end', 'id'])->toArray();
        return array_reverse($calendar_items);
    }

    public function store(Calendar $calendar, Request $request)
    {
        $calendar->fill($request->only(['name', 'start', 'end']));
        $calendar->user_id = Auth::id();
        $calendar->save();

        event(new NotifyUserSchedule(NotificationConst::CALENDAR_MAIL_CREATE, $calendar));
        return response()->json($calendar->id);
    }



    public function update($id, Request $request)
    {
        $calendar = Calendar::find($id);
        $calendar->fill($request->only(['name', 'start', 'end']));
        $calendar->user_id = Auth::id();
        return $calendar->save();
    }

    public function destroy($id)
    {
        $calendar = Calendar::find($id);
        $calendar->delete();

        return response()->json('deleted');
    }
}
