<?php

namespace Tests\Feature;

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


    public function test_get_calendar_event()
    {
        $this->loginedUser();
        $this->createCalendarEvents();
        $response = $this->get('/api/calendar');
        $response->assertOk();
        $response->assertJsonCount(3);
        $this->deleteCalendar();
        $this->deleteUser();
    }
}
