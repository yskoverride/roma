<?php

namespace Tests\Feature;

use App\Models\Schedule;
use Tests\TenantTestCase;
use App\Models\ScheduleUnavailability;

class ScheduleUnavailabilityTest extends TenantTestCase
{
    public function testScheduleUnavailabilityCreationWithValidData()
    {
        // Create a schedule
        $schedule = Schedule::factory()->create();

        // Create a ScheduleUnavailability object with valid data
        $unavailability = ScheduleUnavailability::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        // Check if the ScheduleUnavailability object was successfully created
        $this->assertNotNull($unavailability);
        $this->assertEquals($schedule->id, $unavailability->schedule_id);
    }

    public function testRelationshipBetweenScheduleUnavailabilityAndSchedules()
    {
        // Create a schedule and a ScheduleUnavailability object
        $schedule = Schedule::factory()->create();
        $unavailability = ScheduleUnavailability::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        // Retrieve the schedule associated with the ScheduleUnavailability object
        $retrievedSchedule = $unavailability->schedule;

        // Check if the retrieved schedule matches the created schedule
        $this->assertNotNull($retrievedSchedule);
        $this->assertEquals($schedule->id, $retrievedSchedule->id);
    }



}
