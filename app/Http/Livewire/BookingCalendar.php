<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Livewire\Component;

class BookingCalendar extends Component
{
    
    public $calendarStartDate;
    public $date;
    public $user;
    public $service;
    public $time;

    public function mount()
    {
        $this->calendarStartDate = now();
        $this->setDate(now()->timestamp);
    }

    public function updatedTime($time)
    {
        $this->emitUp('updated-Booking-Time', $time);
    }

    public function resetBookingConfirmation()
    {
        $this->emitUp('resetBookingConfirmation');
    }


    public function getUserScheduleProperty()
    {
        return $this->user->schedules()->whereDate('date',$this->CalendarSelectedDateObject)
                ->first();
    }

    public function getAvailableTimeSlotsProperty()
    {
        if( !$this->user || !$this->userSchedule){
            return collect();
        }

        return $this->user->availableTimeSlots($this->userSchedule, $this->service);
    }

    public function getCalendarSelectedDateObjectProperty()
    {
        return Carbon::createFromTimestamp($this->date);
    }

    public function setDate($timestamp)
    { 
        $this->date = $timestamp;

        $this->time = '';

        $this->emitUp('resetBookingConfirmation');
    }

    public function getCalendarWeekIntervalProperty()
    {
        return CarbonInterval::days(1)
                ->toPeriod(
                    $this->calendarStartDate,
                    $this->calendarStartDate->clone()->addWeek()
                );
    }

    public function incrementCalendarWeek()
    {
        $this->calendarStartDate->addWeek()->addDay();
    }

    public function decrementCalendarWeek()
    {
        $this->calendarStartDate->subWeek()->subDay();
    }

    public function getWeekIsGreaterThanCurrentProperty()
    {
        return $this->calendarStartDate->gt(now());
    }

    
    public function render()
    {
        return view('livewire.booking-calendar');
    }
}
