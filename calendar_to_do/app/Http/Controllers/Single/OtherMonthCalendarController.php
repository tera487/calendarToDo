<?php

namespace App\Http\Controllers\Single;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherMonthCalendarController extends Controller
{
    public function __invoke(Request $request)
    {
        $calendar_items = Calendar::where([['user_id', Auth::id()], ['start',  'LIKE', '%' . (new Carbon($request->selected_month))->format('Y-m') . '%']])->get(['name', 'start', 'end', 'description', 'is_send', 'is_all_day'])->toArray();
        return array_reverse($calendar_items);
    }
}
