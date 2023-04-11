<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Bookings\TimeSlotGenerator;
use Illuminate\Support\Facades\Auth;
use App\Bookings\Filters\AppointmentFilter;
use App\Bookings\Filters\UnavailabilityFilter;
use App\Bookings\Filters\SlotsPassedTodayFilter;

class BookingController extends Controller
{
    public function index()
    {
        
        $schedule = Schedule::find(47);
        $service = Service::find(1);

        $slots = Auth::User()->availableTimeSlots($schedule,$service);
        
        return view('booking',['slots' => $slots]);
    }
}
