<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Schedule;
use Tests\TenantTestCase;
use App\Http\Livewire\ScheduleComponent;

class ScheduleComponentTest extends TenantTestCase
{
    /** @test */
    public function it_renders_the_schedule_component()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings')
            ->assertSeeLivewire('schedule-component');
    }

    /** @test */
    public function it_can_update_user_schedule()
    {
        $user = User::factory()->create();

        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);

        $weekSchedules = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);

            $weekSchedules[] = [
                'date' => $date->format('Y-m-d'),
                'formatted_date' => $date->format('jS M y'),
                'day' => $date->format('l'),
                'available' => true,
                'start' => '08:00',
                'end' => '17:00',
                'is_past' => $date->isPast(),
            ];
        }

        Livewire::actingAs($user)
            ->test(ScheduleComponent::class)
            ->set('weekSchedules', $weekSchedules)
            ->call('save')
            ->assertEmitted('displayNotification');

        $schedule = Schedule::first();

        $this->assertNotNull($schedule);
        $this->assertEquals($user->id, $schedule->user_id);
        $this->assertEquals($weekSchedules[0]['date'], $schedule->date->format('Y-m-d'));
        $this->assertEquals($weekSchedules[0]['start'], $schedule->start_time->format('H:i'));
        $this->assertEquals($weekSchedules[0]['end'], $schedule->end_time->format('H:i'));
    }
}
