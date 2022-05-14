<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use App\Models\Calendar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function loginedUser()
    {
        $this->user = User::factory()->create();
        Sanctum::actingAs(
            $this->user,
            ['view-tasks']
        );

        return $this->user;
    }

    protected function createCalendarEvents()
    {
        $this->calendar = Calendar::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);
    }

    protected function deleteUser()
    {
        User::destroy($this->user->id);
    }

    protected function deleteCalendar(){
        Calendar::where('user_id', $this->user->id)->delete();
    }
}
