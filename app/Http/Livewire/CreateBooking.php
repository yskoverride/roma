<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class CreateBooking extends Component
{
    
    public $users;
    
    public $state = [
        'service' => '',
        'user' => '',
        'time' => '',
        'patient' => '',
    ];

    public $user;
    public $service;

    public $hasDetailsToBook;

    public function mount()
    {
        $this->users = collect();
    }

    protected $listeners = [
        'updated-Booking-Time' => 'setTime',
        'resetBookingConfirmation' => 'resetBookingConfirmation',
        'patientSelected' => 'handlePatientSelected',
    ];

    public function handlePatientSelected($patientId)
    {
        $this->state['patient'] = $patientId;
    }

    public function resetBookingConfirmation()
    {
        $this->clearTime();
        
        $this->hasDetailsToBook = false;
    }

    public function setTime($time)
    {
        $this->state['time'] = $time;
        $this->hasDetailsToBook = true;
        
    }

    public function updatedStateService($serviceId)
    {
        $this->state['user'] = '';

        if(!$serviceId){
            $this->users = collect();
            return;
        }

        $this->clearTime();

        $this->users = $this->selectedService->users;

    }

    public function updatedStateUser()
    {
        $this->clearTime();
    }

    public function clearTime()
    {
        return $this->state['time'] = '';
    }

    public function getSelectedServiceProperty()
    {
        if(!$this->state['service']){
            return null;
        }

        return Service::find($this->state['service']);

    }

    public function getSelectedPatientProperty()
    {
        if(!$this->state['patient']){
            return null;
        }

        return Patient::find($this->state['patient']);
    }

    public function getSelectedUserProperty()
    {
        if(!$this->state['user']){
            return null;
        }

        return User::find($this->state['user']);

    }

    public function getHasDetailsToBookProperty()
    {
        return $this->state['service'] && $this->state['user'] && $this->state['time'];
    }

    public function getTimeObjectProperty()
    {
        return Carbon::createFromTimestamp($this->state['time']);
    }

    public function getServicesProperty()
    {
        return Service::all();
    }

    public function rules()
    {   
        return [
            'state.patient' => 'required|exists:patients,id',
            'state.user' => 'required|exists:users,id',
            'state.service' => 'required|exists:services,id',
            'state.time' => 'required'
        ];
    }

    protected function messages()
    {
        return [
            'state.patient' => 'Please select the patient',
            'state.user' => 'Please select the doctor',
            'state.service' => 'Please select the service',
            'state.time' => 'Please select the date and time of appointment',
        ];
    }

    public function createBooking()
    {
        $this->validate();

        $bookingFields = [
            'date' => $this->timeObject->toDateString(),
            'start_time' => $this->timeObject->toTimeString(),
            'end_time' => $this->timeObject->clone()->addMinutes(
                $this->selectedService->duration)->toTimeString()
        ];

        $appointment = Appointment::make($bookingFields);

        $appointment->service()->associate($this->selectedService);
        $appointment->doctor()->associate($this->selectedUser);
        $appointment->patient()->associate($this->selectedPatient);

        $appointment->save();

        return redirect()->to(route('appointment.show',$appointment).'?token='.$appointment->token);

    }

    
    public function render()
    {
        
        return view('livewire.create-booking',['services' => $this->services]);
    }
}
