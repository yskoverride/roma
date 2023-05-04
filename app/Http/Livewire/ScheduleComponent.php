<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Schedule;
use Illuminate\Contracts\Auth\Authenticatable;

class ScheduleComponent extends Component
{
    public $user;
    public $schedules;
    public $weekSchedules = [];

    protected $rules = [
        'weekSchedules.*.start' => ['required', 'date_format:H:i', 'before:weekSchedules.*.end'],
        'weekSchedules.*.end' => ['required', 'date_format:H:i', 'after:weekSchedules.*.start'],
    ];    

    public function mount(Authenticatable $user)
    {
        $this->user = $user;
        $this->schedules = $user->schedules->isEmpty() ? collect([]) : $user->schedules;
        $this->weekSchedules = $this->loadSchedules();
    }


    public function loadSchedules()
    {
        $weekSchedules = []; // Initialize an empty array to store the week's schedules

        // Set the start of the week to Sunday
        $weekStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);

        // Loop through each day of the week (0-6)
        for ($i = 0; $i < 7; $i++) {
            // Add the current day index to the week start date to get the current day's date
            $date = $weekStart->copy()->addDays($i);

            // Check if there is a schedule for the current day's date
            $schedule = $this->schedules->first(function ($item) use ($date) {
                return $item->date->toDateString() == $date->toDateString();
            });            

            // Add the schedule information to the weekSchedules array
            $weekSchedules[] = [
                'date' => $date->format('Y-m-d'), // The current day's date
                'formatted_date' => $date->format('jS M y'), // The formatted date for display
                'day' => $date->format('l'), // The day of the week (e.g., Monday)
                'available' => (bool) $schedule, // Whether or not a schedule exists for this date
                'start' => $schedule ? $schedule->start_time->format('H:i') : '09:00', // Set the start time, default to 09:00 if no schedule
                'end' => $schedule ? $schedule->end_time->format('H:i') : '17:00', // Set the end time, default to 17:00 if no schedule
                'is_past' => $date->isPast(), // Check if the date is in the past
            ];
            
        }

        // Update the weekSchedules property with the generated schedule information
        $this->weekSchedules = $weekSchedules;

        // Return the weekSchedules array
        return $weekSchedules;
    }


    public function save()
    {
        foreach ($this->weekSchedules as $index => $availability) {
            $schedule = $this->user->schedules()->whereDate('date', $availability['date'])->first();
    
            if ($availability['available']) {
                $data = [
                    'date' => $availability['date'],
                    'user_id' => $this->user->id,
                    'start_time' => $availability['start'],
                    'end_time' => $availability['end'],
                ];
    
                $this->validate([
                    "weekSchedules.$index.start" => ['required', 'date_format:H:i', "before:weekSchedules.$index.end"],
                    "weekSchedules.$index.end" => ['required', 'date_format:H:i', "after:weekSchedules.$index.start"],
                ]);                
    
                if ($schedule) {
                    $schedule->update($data);
                } else {
                    Schedule::create($data);
                }
            } else {
                if ($schedule) {
                    $schedule->delete();
                }
            }
        }

        $this->emitTo('notification-component', 'displayNotification', 'Schedule updated successfully');

        $this->emit('$refresh');
    }
    

    public function toggleAvailability($index)
    {
        $this->weekSchedules[$index]['available'] = !$this->weekSchedules[$index]['available'];
        $this->weekSchedules = array_values($this->weekSchedules); // Add this line
    }

    public function render()
    {
        return view('livewire.schedule-component');
    }
}

