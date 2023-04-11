<?php

namespace App\Bookings\Filters;

use App\Bookings\Filter;
use App\Bookings\TimeSlotGenerator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AppointmentFilter implements Filter
{
    
    protected $appointments;
    
    public function __construct(Collection $appointments)
    {
        $this->appointments = $appointments;
    }
    
    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function($slot) use($generator) {
            foreach($this->appointments as $appointment){
                if(
                    $slot->between(
                        $appointment->date->setTimeFrom(
                            $appointment->start_time->subMinutes
                            ($generator->service->duration)
                        ),
                        $appointment->date->setTimeFrom(
                            $appointment->end_time->subMinutes
                            ($generator->service->duration)
                        ),
                    )
                )
                return false;
            }
            
            return true;
        });
    }
}