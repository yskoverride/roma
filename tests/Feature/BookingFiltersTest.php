<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Service;
use App\Models\Schedule;
use Carbon\CarbonPeriod;
use Tests\TenantTestCase;
use App\Models\Appointment;
use App\Models\ScheduleUnavailability;
use App\Bookings\Filters\AppointmentFilter;

class BookingFiltersTest extends TenantTestCase
{
    public function testAppointmentFilter()
    {
        // Create a User
        $user = User::factory()->create();

        // Create a Service
        $service = Service::factory()->create(['duration' => 30]);

        // Create a Schedule
        $today = now()->addDays(1)->startOfDay();
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

        // Retrieve all time slots without applying any filters
        $allSlots = $user->availableTimeSlots($schedule, $service);

        // Display all generated time slots
        echo "All time slots:\n";
        foreach ($allSlots as $slot) {
            echo $slot->format('H:i') . "\n";
        }

        // Retrieve available time slots with AppointmentFilter
        $slots = $user->availableTimeSlots($schedule, $service, [new AppointmentFilter($user->appointmentsForDate($schedule->date))]);

        // Display filtered time slots
        echo "Filtered time slots:\n";
        foreach ($slots as $slot) {
            echo $slot->format('H:i') . "\n";
        }

        // Assert that the time slots array is not empty
        $this->assertNotEmpty($slots);

        // info("Appointment time range: {$appointmentStartTime->format('H:i')} - {$appointmentEndTime->format('H:i')}");

        // Assert that none of the time slots overlap with the existing appointment
        $overlapping = false;
        foreach ($slots as $slot) {
            info("Generated time slot: {$slot->format('H:i')}");
            if ($slot->isBetween($appointmentStartTime, $appointmentEndTime, false) || $slot->equalTo($appointmentStartTime)) {
                info("Overlapping time slot: {$slot->format('H:i')}");
                $overlapping = true;
                break;
            }
        }
        $this->assertFalse($overlapping);
    }

    public function testSlotsPassedTodayFilter()
    {
        // Create a User
        $user = User::factory()->create();

        // Create a Service
        $service = Service::factory()->create(['duration' => 30]);

        // Create a Schedule
        $tomorrow = now()->addDay()->startOfDay();
        $schedule = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $tomorrow,
            'start_time' => $tomorrow->copy()->addHours(9),
            'end_time' => $tomorrow->copy()->addHours(17),
        ]);

        // Retrieve available time slots
        $slots = $user->availableTimeSlots($schedule, $service);

        // Assert that the available time slots are generated correctly
        $this->assertInstanceOf(CarbonPeriod::class, $slots);

        // Convert CarbonPeriod to an array
        $slotsArray = $slots->toArray();

        // Assert that there is at least one time slot available
        $this->assertNotEmpty($slotsArray);

        // Assert that none of the time slots have already passed today
        $currentTime = Carbon::now();
        $futureSlots = array_filter($slotsArray, function ($slot) use ($currentTime) {
            return $slot->greaterThanOrEqualTo($currentTime);
        });

        // Assert that the count of future slots is equal to the count of all slots
        $this->assertCount(count($slotsArray), $futureSlots);
    }


    public function testUnavailabilityFilter()
    {
        // Create a User
        $user = User::factory()->create();

        // Create a Service
        $service = Service::factory()->create(['duration' => 30]);

        // Create a Schedule
        $today = now()->addDay()->startOfDay();
        $schedule = Schedule::factory()->create([
            'user_id' => $user->id,
            'date' => $today,
            'start_time' => $today->copy()->addHours(9),
            'end_time' => $today->copy()->addHours(17),
        ]);

        // Create a ScheduleUnavailability
        $unavailabilityStartTime = $today->copy()->addHours(13);
        $unavailabilityEndTime = $today->copy()->addHours(14);
        ScheduleUnavailability::factory()->create([
            'schedule_id' => $schedule->id,
            'start_time' => $unavailabilityStartTime,
            'end_time' => $unavailabilityEndTime,
        ]);

        // Retrieve available time slots
        $slots = $user->availableTimeSlots($schedule, $service);

        // Assert that the available time slots are generated correctly
        $this->assertInstanceOf(CarbonPeriod::class, $slots);

        // Convert CarbonPeriod to an array
        $slotsArray = $slots->toArray();

        // Assert that there is at least one time slot available
        $this->assertNotEmpty($slotsArray);

        // Assert that none of the time slots fall within the unavailability period
        $unavailableSlots = array_filter($slotsArray, function ($slot) use ($unavailabilityStartTime, $unavailabilityEndTime) {
            return $slot->between($unavailabilityStartTime, $unavailabilityEndTime->subMinute());
        });

        // Assert that the count of unavailable slots is zero
        $this->assertCount(0, $unavailableSlots);
    }


}
