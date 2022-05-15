<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class calendarTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_redirect()
    {
        $response = $this->get('/todo');
        $response->assertLocation('/');
        $response->assertOk();
    }

    // public function test_task_list_can_be_retrieved()
    // {
    //     Sanctum::actingAs(
    //         User::factory()->create(),
    //         ['view-tasks']
    //     );

    //     $response = $this->get('/todo');
    //     $response->assertLocation('/todo');
    //     $response->assertOk();
    // }

    private function getDemoCalendar()
    {
        $now = Carbon::now();
        return
            $this->demoCalender = [
                'name' => "test terao",
                'description' => "これはテストです",
                'is_send' => 0,
                'start' => $now->toDateTimeString(),
                'end' => $now->addDays(5)->toDateTimeString(),
                'user_id' => $this->user->id,
            ];
    }

    public function test_index_calendar_event()
    {
        $this->loginedUser();
        $this->createCalendarEvents();
        $response = $this->get('/api/calendar');
        $response->assertOk();
        $response->assertJsonCount(3);
        $this->deleteCalendar();
        $this->deleteUser();
    }

    public function test_store_calendar_event()
    {
        $this->user = $this->loginedUser();
        $response = $this->postJson('/api/calendar', $this->getDemoCalendar());
        $response->assertOk();
        $this->assertDatabaseHas('calendars', $this->demoCalender);
        $this->deleteCalendar();
        $this->deleteUser();
    }
}
