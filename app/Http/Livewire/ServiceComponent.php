<?php

namespace App\Http\Livewire;

use App\Models\Service;
use Livewire\Component;

class ServiceComponent extends Component
{
    public $name;
    public $duration;

    public function render()
    {
        $services = Service::all();
        return view('livewire.service-component', compact('services'));
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:2',
            'duration' => 'required|in:10,15,30,45,60,120',
        ]);

        Service::create([
            'name' => $this->name,
            'duration' => $this->duration,
        ]);

        $this->name = '';
        $this->duration = '';

        $this->emitTo('notification-component', 'displayNotification', 'Service added sucessfully');
    }

    protected function messages()
    {
        return [
            'name.required' => 'Enter the name of service',
            'name.max' => 'Name of service must not be greater than 255 characters',
            'name.min' => 'Name of service should have minimum 2 characters',
            'duration.required' => 'Select the service duration',
        ];
    }
}
