<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\ScheduleUnavailability;
use Illuminate\Contracts\Auth\Authenticatable;

class UnavailabilityComponent extends Component
{
    public $user;
    public $lunchBreak;
    public $minDate;
    public $maxDate;

    protected $rules = [
        'lunchBreak.start' => ['required', 'date_format:H:i', 'before:lunchBreak.end'],
        'lunchBreak.end' => ['required', 'date_format:H:i', 'after:lunchBreak.start'],
    ];

    public function mount(Authenticatable $user)
    {
        $this->user = $user;
        $this->lunchBreak = ['start' => '12:00', 'end' => '13:00'];

        $this->minDate = Carbon::now()->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
        $this->maxDate = Carbon::now()->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');

    }

    public function saveLunchBreak()
    {
        $schedules = $this->user->schedules()
            ->whereBetween('date', [$this->minDate, $this->maxDate])
            ->get();

        foreach ($schedules as $schedule) {
            $scheduleDate = $schedule->date->toDateString();
            $scheduleStartTime = Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $schedule->start_time->format('H:i'));
            $scheduleEndTime = Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $schedule->end_time->format('H:i'));

            $lunchStart = Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $this->lunchBreak['start']);
            $lunchEnd = Carbon::createFromFormat('Y-m-d H:i', $scheduleDate . ' ' . $this->lunchBreak['end']);
            

            if ($lunchStart->greaterThanOrEqualTo($scheduleStartTime) && $lunchEnd->lessThanOrEqualTo($scheduleEndTime)) {
                $data = [
                    'schedule_id' => $schedule->id,
                    'start_time' => $lunchStart,
                    'end_time' => $lunchEnd,
                ];

                $unavailability = $schedule->unavailabilities()
                    ->where('start_time', '>=', $lunchStart)
                    ->where('end_time', '<=', $lunchEnd)
                    ->first();


                if ($unavailability) {
                    $unavailability->update($data);
                } else {
                    ScheduleUnavailability::create($data);
                }
            }
        }

        $this->emitTo('notification-component', 'displayNotification', 'Lunch unavailability saved for this week.');

    }


    public function render()
    {
        return view('livewire.unavailability-component');
    }
}