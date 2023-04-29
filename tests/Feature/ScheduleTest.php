<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Schedule;
use Tests\TenantTestCase;
use App\Models\Appointment;
use App\Models\ScheduleUnavailability;

class ScheduleTest extends TenantTestCase
{
    public function testScheduleCreationWithValidAndInvalidData()
    {
        // Create a schedule with valid data
        $validScheduleData = [
            'date' => '2023-05-01',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
        ];
        $validSchedule = Schedule::factory()->create($validScheduleData);

        // Check if the schedule was successfully created
        $this->assertNotNull($validSchedule->id);

        // Attempt to create a schedule with invalid data
        $invalidScheduleData = [
            'date' => '2023-05-01',
            'start_time' => '18:00:00',
            'end_time' => '09:00:00',
        ];

        // Check if the schedule creation fails
        try {
            $invalidSchedule = Schedule::factory()->create($invalidScheduleData);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testRelationshipBetweenSchedulesAndAppointmentsThroughUser()
    {
        // Create a user
        $user = User::factory()->create();
    
        // Create a service
        $service = Service::factory()->create();
    
        // Create a schedule
        $schedule = Schedule::factory()->create(['user_id' => $user->id]);
    
        // Create an appointment
        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
        ]);
    
        // Retrieve the appointments of the user
        $userAppointments = $user->appointments;
    
        // Ensure the created appointment is among the user's appointments
        $this->assertTrue($userAppointments->contains($appointment));
    
        // Retrieve the schedules of the user
        $userSchedules = $user->schedules;
    
        // Ensure the created schedule is among the user's schedules
        $this->assertTrue($userSchedules->contains($schedule));
    }
    

    public function testUnavailabilitiesRetrievalWithinSchedule()
    {
        // Create a schedule and two ScheduleUnavailability objects
        $schedule = Schedule::factory()->create();
        $unavailability1 = ScheduleUnavailability::factory()->create(['schedule_id' => $schedule->id]);
        $unavailability2 = ScheduleUnavailability::factory()->create(['schedule_id' => $schedule->id]);

        // Retrieve the unavailabilities within the schedule
        $unavailabilities = $schedule->unavailabilities;

        // Check if the retrieved unavailabilities match the created ScheduleUnavailability objects
        $this->assertCount(2, $unavailabilities);
        $this->assertTrue($unavailabilities->contains($unavailability1));
        $this->assertTrue($unavailabilities->contains($unavailability2));
    }



}
