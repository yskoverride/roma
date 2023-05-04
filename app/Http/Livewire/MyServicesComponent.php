<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Service;

class MyServicesComponent extends Component
{
    public $user;
    public $services;
    public $selectedServices = [];

    public function mount(Authenticatable $user)
    {
        $this->user = $user;
        $this->services = Service::all();
        $this->selectedServices = $this->user->services->pluck('id')->toArray();
    }

    public function updateServices()
    {
        $this->user->services()->sync($this->selectedServices);

        $this->emitTo('notification-component', 'displayNotification', 'Your services have been updated successfully.');
    }

    public function render()
    {
        return view('livewire.my-services-component');
    }
}

