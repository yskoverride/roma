<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Schedule;
use Tests\TenantTestCase;
use App\Models\Appointment;


class UserTimeSlotTest extends TenantTestCase
{
    public function testAvailableTimeSlots()
    {
              
        // Create a User
        $user = User::factory()->create();

        // Create Services
        $service1 = Service::factory()->create(['duration' => 30]);
        $service2 = Service::factory()->create(['duration' => 60]);

        //Map Users with Services
        $user->services()->attach($service1->id);
        $user->services()->attach($service2->id);

        // Create Schedules
        $tomorrow = now()->addDays(1)->startOfDay();
        $dayAfter = now()->addDays(2)->startOfDay();
        $schedule1 = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $tomorrow,
            'start_time' => $tomorrow->copy()->addHours(9),
            'end_time' => $tomorrow->copy()->addHours(17),
        ]);
        $schedule2 = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $dayAfter,
            'start_time' => $dayAfter->copy()->addHours(10),
            'end_time' => $dayAfter->copy()->addHours(16),
        ]);

        // Retrieve available time slots
        $slots1 = $user->availableTimeSlots($schedule1, $service1);
        $slots2 = $user->availableTimeSlots($schedule1, $service2);
        $slots3 = $user->availableTimeSlots($schedule2, $service1);
        $slots4 = $user->availableTimeSlots($schedule2, $service2);

        // Assert the number of time slots for each combination
        $this->assertCount(16, $slots1); // 16 slots of 15-min intervals for 8 hours with 30-min duration service
        $this->assertCount(8, $slots2); // 8 slots of 15-min intervals for 8 hours with 60-min duration service
        $this->assertCount(12, $slots3); // 12 slots of 15-min intervals for 6 hours with 30-min duration service
        $this->assertCount(6, $slots4); // 6 slots of 15-min intervals for 6 hours with 60-min duration service
    }


    public function testAppointmentsFilteredFromTimeSlots()
    {
        
        // Create a User
        $user = User::factory()->create();

        // Create a Service
        $service = Service::factory()->create(['duration' => 30]);

        // Set up the relationship between the user and the service
        $user->services()->attach($service->id);

        // Create a Schedule
        $today = now()->startOfDay();
        $schedule = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $today,
            'start_time' => $today->copy()->addHours(9),
            'end_time' => $today->copy()->addHours(17),
        ]);

        // Create an Appointment
        $appointmentStartTime = $today->copy()->addHours(11)->addMinutes(30);
        $appointmentEndTime = $appointmentStartTime->copy()->addMinutes($service->duration);
        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'date' => $today,
            'start_time' => $appointmentStartTime,
            'end_time' => $appointmentEndTime,
        ]);

        // Retrieve available time slots
        $slots = $user->availableTimeSlots($schedule, $service);

        // Assert that none of the time slots overlap with the existing appointment
        foreach ($slots as $slot) {
            $slotEndTime = $slot->copy()->addMinutes($service->duration);
            $appointmentPeriod = new \Carbon\CarbonPeriod($appointmentStartTime, $appointmentEndTime);
            $slotPeriod = new \Carbon\CarbonPeriod($slot, $slotEndTime);
            $this->assertFalse($appointmentPeriod->overlaps($slotPeriod));
        }
    }


}
