<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Schedule;
use Tests\TenantTestCase;
use App\Http\Livewire\UnavailabilityComponent;

class UnavailabilityComponentTest extends TenantTestCase
{
    /** @test */
    public function it_renders_the_unavailability_component()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings')
            ->assertSeeLivewire('unavailability-component');
    }

    /** @test */
    public function it_can_set_lunch_break_unavailability()
    {
        $user = User::factory()->create();
        // Ensure the schedule is created within the same week
        $dateWithinThisWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY)->addDays(6);
        $schedule = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $dateWithinThisWeek,
        ]);

        $lunchBreak = [
            'start' => '12:00',
            'end' => '13:00',
        ];

        Livewire::actingAs($user)
            ->test(UnavailabilityComponent::class)
            ->set('lunchBreak', $lunchBreak)
            ->call('saveLunchBreak')
            ->assertEmitted('displayNotification', 'Lunch unavailability saved for this week.');

        
        $unavailability = $schedule->unavailabilities->first();

        $this->assertNotNull($unavailability);
        $this->assertEquals(Carbon::createFromFormat('H:i', $lunchBreak['start'])->format('H:i'), $unavailability->start_time->format('H:i'));
        $this->assertEquals(Carbon::createFromFormat('H:i', $lunchBreak['end'])->format('H:i'), $unavailability->end_time->format('H:i'));
    }
}
